<?php
namespace Anax\Com;

/**
 * A controller for comments and admin related events.
 *
 */
class ComController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Initialize the controller.
     *
     * @return void
     */

    public function initialize()
    {
        $this->answers = new \Anax\Answers\Answers(); //skapa ett objekt
        $this->answers->setDI($this->di); //injektar $di i objektet

        $this->comments = new \Anax\Comments3\Comments3(); //skapa ett objekt
        $this->comments->setDI($this->di); //injektar $di i objektet

        $this->questions = new \Anax\Questions\Questions(); //skapa ett objekt
        $this->questions->setDI($this->di); //injektar $di i objektet

        $this->com = new \Anax\Com\Com(); //skapa ett objekt
        $this->com->setDI($this->di); //injektar $di i objektet
    }


    public function listAction()
    {
        $all = $this->com->findAll(); //metoden 'findAll()' hämtar alla users


        //Lämnar över till vyn att skriva ut alla users
        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "CommentsOnQuestions",
        ]);
    }




        //att kommentera ett svar sker i två delar
        //först skickas ett formulär till användaren
        //sedan sparas svaret ifrån formuläret

        //som argument tas frågans id, så att svaret kan bindas till den.

    public function comAction($aId = null)
    {
        //kontrollera så att användaren är inloggad
        $usersArr = $this->session->get('user', []);


        if ($usersArr != null) {

            if ($usersArr[0] == null) {
                $content = "You need to login to see this site";
            } else {
                $uName = $usersArr[1];
                $uId = $usersArr[0];



                $content= "
                <form method='post' action='../addCom'>
                    <input type='hidden' name='user' value=$uId>
                    <input type='hidden' name='answer' value=$aId>

                    <h3> Kommentar : </h3>
                    <textarea rows='5' cols='60' name='comment'></textarea><br/>

                    <input type='submit' value='kommentera'>
                    <br/><br/>

                </form>
                ";

            }

        } else {
            $content = "Log in please !";
        }

        //Lämnar över till vyn att skriva ut alla users
        $this->theme->setTitle("Skriv kommentar på fråga");
        $this->views->add('questions/list-all', [
            'questions' => $content,
            'title' => "Kommentera"
        ]);


    }

    public function addComAction()
    {

        //ta emot parametrar

                $uId = $_POST['user'];
                $aId = $_POST['answer'];
                $aCom = $_POST['comment'];



        //spara infon i databasen
                $this->com->save([
                    'user' => $uId,
                    'answer' => $aId,
                    'commentNew' => $aCom
                    ]);

                    $content = ":)";

                    //Lämnar över till vyn att skriva ut svar
                    $this->theme->setTitle("Kommentar registrerad");
                    $this->views->add('questions/list-all', [
                        'questions' => $content,
                        'title' => "Kommentaren registrerad"
                    ]);

    }
}
