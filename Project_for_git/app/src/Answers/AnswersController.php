<?php
namespace Anax\Answers;

/**
 * A controller for users2 and admin related events.
 *
 */
class AnswersController implements \Anax\DI\IInjectionAware
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

        $this->questions = new \Anax\Questions\Questions(); //skapa ett objekt
        $this->questions->setDI($this->di); //injektar $di i objektet
    }



    public function listAction()
    {
            $all = $this->answers->findAll(); //metoden 'findAll()' hämtar alla users

            //Lämnar över till vyn att skriva ut alla users
            $this->theme->setTitle("List all answers");
            $this->views->add('users/list-all', [
                'users' => $all,
                'title' => "View all answers",
            ]);
    }


    public function answerAction($idNr = null)
    {

        $qtion = null;

        //gör en kontrolll så att användaren är inloggad
        //är användaren inloggad så används de uppgifterna
        //o användaren får tillbaka ett formulär att fylla i.

        $usersArr = $this->session->get('user', []);

        if ($usersArr != null) {
            $uName = $usersArr[1];
            if ($usersArr[0] == null) {
                $content = "You need to login to see this site";
            } else {
                //tar fram frågan via id:
                //hämta ut namn på den som svarat
                $qtionObj = $this->questions->query()
                            ->where('id = ?')
                            ->execute([$idNr]);

                $thisQ = $qtionObj[0];
                $qtion = $thisQ->question;

                $userId = $usersArr[0];
                $content = "<form method='post' action='../answer2'>
                            <input type='hidden' name='question' value=$idNr>
                            <input type='hidden' name='user' value=$userId>
                            <textarea rows='5' cols='40' name='answer'></textarea></br>
                            <input type='submit' value='svara'>
                        </form> <br/> ";
            }
        } else {
            $content = "Log in please !";
        }

        //Lämnar över till vyn att skriva ut alla users
        $this->theme->setTitle("Answer");
        $this->views->add('questions/answer', [
            'question' => $qtion,
            'answer' => $content,
            'title' => "Svara",
        ]);
    }


    public function answer2Action()
    {

        $usersArr = $this->session->get('user', []);
        $id = $usersArr[0];

        $question = $_POST['question'];
        $user = $_POST['user'];
        $answer = $_POST['answer'];

        //markdown
        $answer = $this->textFilter->doFilter($answer, 'shortcode, markdown');

        $this->answers->save([
            'user' => $id,
            'question' => $question,
            'answer' => $answer
        ]);

        $qtion = "Tack";
        $content = "Ditt svar har registrerats !";

        //Lämnar över till vyn att skriva ut alla users
        $this->theme->setTitle("Answer");
        $this->views->add('questions/answer', [
            'question' => $qtion,
            'answer' => $content,
            'title' => "Svara",
        ]);


    }
}
