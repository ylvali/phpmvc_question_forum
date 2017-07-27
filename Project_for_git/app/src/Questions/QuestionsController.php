<?php
namespace Anax\Questions;

/**
 * A controller for users2 and admin related events.
 *
 */
class QuestionsController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Initialize the controller.
     *
     * @return void
     */

    public function initialize()
    {
        //users
        $this->user2 = new \Anax\Users2\User2;
        $this->user2->setDI($this->di);

        //answers
        $this->answers = new \Anax\Answers\Answers;
        $this->answers->setDI($this->di);

        //questions
        $this->question = new \Anax\Questions\Questions(); //skapa ett objekt
        $this->question->setDI($this->di); //injektar $di i objektet

        //comments
        $this->comments = new \Anax\Comments3\Comments3(); //skapa ett objekt
        $this->comments->setDI($this->di); //injektar $di i objektet

        //comments on comments
        $this->com = new \Anax\Com\Com(); //skapa ett objekt
        $this->com->setDI($this->di); //injektar $di i objektet

    }

    //Denna funktionen listar upp alla frågor,
    //skiver ut tillhörande svar och kommentarer.

    public function listAction()
    {
        //hämta ut alla frågor som objekt ur databasen
            $allQ = $this->question->findAll(); //metoden 'findAll()' hämtar alla questions

            $questionInfo = null;

            //Sedan ska objektInfo skrivas ut snyggt
        foreach ($allQ as $qtion) {
                $Qid = $qtion->id; //vilket id har frågan
                $user = $qtion->user; //vilken användare har ställt frågan
                $subject = $qtion->subject; //vilka tags
                $question = $qtion->question; //vad är frågan



                //hämta ut namn på user
                //då det enbart är id som identifiering i databasen
                //unikt för var användare.... så används den som identifierare
                //men ser trevligare ut att ha ett namn
                //så via userModell tas userObjektet så tas namn fram från databasen
                $nameUser = $this->user2->query()
                            ->where('id = ?')
                            ->execute([$user]);


                //sedan tar vi fram själva namnet
                $userName = null;
            foreach ($nameUser as $usR) {
                    $userName = $usR->name;



                //Hämtar också ut alla svar som hänger ihop m det id:t
                //alltså
                //på specifik fråga.

                //fråge objektet tas fram och varje fråga skrivs ut.
                //det kontrolleras också om det finns någon kommentar på frågan

                $answersQ = $this->answers->query()
                            ->where('question = ?')
                            ->execute([$Qid]);

                //foreach tar fram värden ur array
                    $nme = null;
                    $ansR = null;

                foreach ($answersQ as $nsr) {
                    $idAnswer = $nsr->id;
                    $nme = $nsr->user;
                    $ansR .= "<hr/><br/>";
                    $ansR .= $nsr->answer;


                    //hämta ut namn på den som svarat
                    //hämta ut id på svar för o se om det finns kommentarer
                    $nme = $this->user2->query()
                                ->where('id = ?')
                                ->execute([$nme]);

                            $nameAnswer = null;
                    foreach ($nme as $object) {
                        $nameAnswer = $object->name;
                    }

                    $ansR .= "<br/> - $nameAnswer<br/><br/>
                    <a href='../com/com/$idAnswer' alt='kommentera'> Kommentera svaret </a><br/><br/>";


                    //hämta ut ev kommentar på svaret
                    //hämta ut eventuella kommentarer på svaret
                    $comCom = $this->com->query()
                                ->where('answer = ?')
                                ->execute([$idAnswer]);


                    //hämta ut infon ur objektet
                        $ccCom = null;
                    foreach ($comCom as $comObj) {
                        $ccUser = $comObj->user;
                        $ccCom = $comObj->commentNew;


                        //hämta ut namn på den som svarat
                        $nameCC = $this->user2->query()
                                    ->where('id = ?')
                                    ->execute([$ccUser]);

                                $nCC = null;
                        foreach ($nameCC as $obj) {
                            $nCC = $obj->name;
                        }

                        $ansR .= "<div class='comcom'>
                                <br/><br/>
                                $ccCom <br/><br/>
                                -$nCC <br/> </div>";
                    }
                } //slut på foreach för frågeobjekt


                //hämta ut eventuell kommentarer knytna till frågan
                    $commentsQ = $this->comments->query()
                            ->where('question = ?')
                            ->execute([$Qid]);


                //ta fram värden ur arrayen
                    $nameC = null;
                    $comC = null;

                foreach ($commentsQ as $cmnt) {

                        $idC = $cmnt->id;
                        $nameC = $cmnt->user;
                        $comC .= "<hr/><br/>";
                        $comC .= $cmnt->comment;


                    //hämta ut namn på den som svarat
                        $nameCmnt = $this->user2->query()
                                ->where('id = ?')
                                ->execute([$nameC]);

                            $nameAnswer = null;
                    foreach ($nameCmnt as $objct) {
                            $nameComment = $objct->name;
                    }
                        $comC .= "<br/> - $nameComment <br/><br/><hr/>

                    ";

                } //slut på foreach angående kommentarer




                //Gör en string att presentera
                $questionInfo .= "<h3>Id:$Qid</h3><br/>
                                <h3>Användare:</h3> $userName <br/><br/>
                                <h3>Ämne:</h3> $subject <br/><br/>
                                <h3>Fråga:</h3> <div class='question'>$question </div><br/><br/><br/><hr/>
                                <div id='answer'> <fieldset>
                                <h3> Svar: </h3>
                                $ansR
                                <br/>
                                <br/>
                                <h3> Kommentarer på frågan </h3>
                                $comC
                                <br/>
                                <br/>
                                <hr/> </fieldset> </div> <br/>
                                <a href='../answers/answer/$Qid' alt='svar'> Svara</a>
                                | <a href='../comments3/comment/$Qid' alt='kommentar'> Kommentera </a> <br/><hr/>
                                <br/><br/><br/>";


            }
        }

            //gör en kontrolll så att användaren är inloggad

            $usersArr = $this->session->get('user', []);
            $content = null;

        if ($usersArr != null) {
                $uName = $usersArr[1];
            if ($usersArr[0] == null) {
                    $content = "You need to login to see this site";
            } else {
                    $content = $questionInfo;
            }
        } else {
                $content = "Log in please !";
        }


            //Lämnar över till vyn att skriva ut alla users
            $this->theme->setTitle("List all questions");
            $this->views->add('questions/list-all', [
                'questions' => $content,
                'title' => "Alla frågor",
            ]);
    }

        //hämtar ut de tre senaste frågorna
    public function theLatestQAction()
    {

            $latestQstions = $this->question->query()
                        ->orderby('timecheck DESC')
                        ->execute();

                $latestQ = null;
        foreach ($latestQstions as $object) {
                $id = $object->id;
                $user = $object->user;
                $subject = $object->subject;
                $question = $object->question;
                $timeCheck = $object->timeCheck;

                $latestQ .= "<h3> Id: $id </h3>
                <h3> User: $user </h3>
                <h3> Subjects </h3> : $subject <br/>
                <h3> Question </h3> : $question <br/>
                <h3> TimeCheck: </h3> : $timeCheck <br/><hr/>";
        }


                        //Lämnar över till vyn att skriva ut alla users
                        $this->theme->setTitle("List newest questions");
                        $this->views->add('questions/list-all', [
                            'questions' => $latestQ,
                            'title' => "Senaste frågorna",
                        ]);
    }

    //lägg till en ny fråga
    //sker i två steg - först ge användaren ett formulär...
    //..och sedan ta emot frågan och spara i databasen

    public function addQuestionAction()
    {

        //kontrollera så att användaren är inloggad
        $usersArr = $this->session->get('user', []);



        if ($usersArr != null) {

            if ($usersArr[0] == null) {
                $content = "You need to login to see this site";
            } else {
                $uName = $usersArr[1];
                $uId = $usersArr[0];

                $now = gmdate('Y-m-d H:i:s');

                $content= "
                <form method='post' action='addQuestion2'>
                    <input type='hidden' name='user' value=$uId>

                    <h3> Fråga : </h3>
                    <textarea rows='5' cols='60' name='question'></textarea><br/>

                    <h3> Kryssa i ämne: </h3>
                    <input type='checkbox' name='subject[]' value='beauty'> Skönhet <br/>
                    <input type='checkbox' name='subject[]' value='technique'> Teknik <br/>
                    <input type='checkbox' name='subject[]' value='history'> Historia <br/>
                    <input type='checkbox' name='subject[]' value='material'> Material <br/>
                    <input type='checkbox' name='subject[]' value='form'> Form <br/>

                    <input type='hidden' name='timeCheck' value=$now>
                    <br/><br/>
                    <input type='submit' value='fråga'>
                    <br/><br/>

                </form>
                ";

            }

        } else {
            $content = "Log in please !";
        }

        //Lämnar över till vyn att skriva ut alla users
        $this->theme->setTitle("Add question");
        $this->views->add('questions/list-all', [
            'questions' => $content,
            'title' => "Fråga en fråga"
        ]);


    }

    //Nästa del tar emot parametrarna som skickades och lägger in dem
    //i databasen

    public function addQuestion2Action()
    {

        $uId = $_POST['user'];
        $question = $_POST['question'];
        $subject = $_POST['subject'];
        $time = $_POST['timeCheck'];

        //kör markdown på texten
         $question = $this->textFilter->doFilter($question, 'shortcode, markdown');

        //hämta ut arrayen med subject och lägg dem i en listA
        $size = sizeOf($subject);

            $theSubjects = null;
        for ($i=0; $i<$size; $i++) {
            $theSubjects .= $subject[$i];
            $theSubjects .= " ";
        }

        //spara infon i databasen
        $this->question->save([
            'user' => $uId,
            'subject' => $theSubjects,
            'question' => $question,
            'timeCheck' => $time,
        ]);

        $questions = "The question has been asked!";


        //Lämnar över till vyn att skriva ut alla users
        $this->theme->setTitle("Add question");
        $this->views->add('questions/list-all', [
            'questions' => $questions,
            'title' => "Fråga en fråga"
        ]);
    }

    //en funktion som tar fram alla frågor under valt subject/tag
    public function showSubjectAction($tag = null)
    {

        $subjectQ = $this->question->query()
                    ->where('subject LIKE ?')
                    ->execute(["%$tag%"]);

                $presentation = null;
        foreach ($subjectQ as $object) {
            $id = $object->id;
            $user = $object->user;
            $subject = $object->subject;
            $question = $object->question;

            //hämta ut namn på user
            $nameUser = $this->user2->query()
                        ->where('id = ?')
                        ->execute([$user]);

            $userName = null;
            foreach ($nameUser as $usR) {
                $userName = $usR->name;
            }

            $presentation .= "<h2>Id: $id </h2>
                            <h2> User: $userName </h2>
                            <h2> Subject: </h2>
                            $subject <br/>
                            <h2> Question: </h2>
                            $question <br/> <br/> <hr/>" ;

        }

                    //Lämnar över till vyn att skriva ut alla users
                    $this->theme->setTitle("Tags");
                    $this->views->add('questions/list-all', [
                        'questions' => $presentation,
                        'title' => $tag
                    ]);


    }
}
