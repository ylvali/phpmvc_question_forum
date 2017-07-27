<?php
namespace Anax\Comments3;

/**
 * A controller for comments and admin related events.
 *
 */
class Comments3Controller implements \Anax\DI\IInjectionAware
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
    }


    public function listAction()
    {
        $all = $this->comments->findAll(); //metoden 'findAll()' hämtar alla users


        //Lämnar över till vyn att skriva ut alla users
        $this->theme->setTitle("List all users");
        $this->views->add('users/list-all', [
            'users' => $all,
            'title' => "View all users",
        ]);
    }


    //denna funktion tar fram ett formulär som är knutet till en specifik
    //fråga samt användare .

    //Formuläret skickas ut i denna funktion och tas emot i nästa

    public function commentAction($id = null)
    {
        //hämta ut usern:
        $usersArr = $this->session->get('user', []);

        //kontrollera att användaren e inloggad
        if ($usersArr != null) {
            $uName = $usersArr[1];
            if ($usersArr[0] == null) {
                $content = "You need to login to see this site";
            } else {
                //tar fram frågan via id:
                //hämta ut namn på den som svarat
                $qtionObj = $this->questions->query()
                            ->where('id = ?')
                            ->execute([$id]);

                $thisQ = $qtionObj[0];
                $qtion = $thisQ->question;

                $userId = $usersArr[0];

                $content = "<form method='post' action='../comment2'>
                            <input type ='hidden' name='question' value=$id>
                            <input type ='hidden' name='user' value=$uName>
                            <textarea rows='4' cols='50' name='comment'></textarea> <br/>
                            <input type='submit' value='kommentera'>
                        </form>";
            }
        } else {
            $content = "Log in please !";
        }



        //Lämnar över till en vy som fungerar att skriva ut formuläret
        $this->theme->setTitle("Add comment");
        $this->views->add('comments3/comments3', [
            'question' => $qtion,
            'info' => $content,
            'title' => "Kommentera ett inlägg",
        ]);
    }


    //I denna funktionen så tas användarens kommentar fram
    //...och den sparas till databasen.
    public function comment2Action()
    {

        //hämta användare
        $usersArr = $this->session->get('user', []);
        $id = $usersArr[0];

        $question = $_POST['question'];
        $user = $_POST['user'];
        $comment = $_POST['comment'];

        //kör markdown på kommentar
        $comment = $this->textFilter->doFilter($comment, 'shortcode, markdown');

        $this->comments->save([
            'user' => $id,
            'question' => $question,
            'comment' => $comment
        ]);




        $qtion = "Tack";
        $content = "Din kommentar har registrerats !";

        //Lämnar över till vyn att skriva ut alla users
        $this->theme->setTitle("Comment");
        $this->views->add('comments3/comments3', [
            'question' => $qtion,
            'info' => $content,
            'title' => "Kommentera",
        ]);

    }
}
