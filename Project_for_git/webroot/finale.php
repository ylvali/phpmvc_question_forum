<?php

//Frontcontroller
// ... del Anax-mvcphp ramverk

//Ta med den mkt viktiga config filen
require __DIR__.'/config_with_app.php';

//skapa en navbar
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_finale.php');

//skapa ett theme
$app->theme->configure(ANAX_APP_PATH . 'config/theme_finale.php');

//------------------------routes

$app->router->add('', function () use ($app) {

    //kontroll ifall det finns en användare inloggad
    //Hämtar genom objektet $app ut sessionen
    //kontrollerar sedan om den existerar m värden, o isf
    //kontrollerar lösenord...


    $usersArr = $app->session->get('user', []);

    if ($usersArr != null) {
        $uName = $usersArr[1];

        if ($usersArr[0] == null) {
            $content = "You need to login to see this site";
        } else {
            $content = "$uName is logged in <br/><br/>
            En sida tillägnad smyckeskonst och frågor därikring. <br/><br/>";

            //hämta ut de tre sista frågorna ifrån databasen
            $app->db->select()
                     ->from('questions ORDER BY timeCheck DESC LIMIT 3');
            $app->db->execute();
            $questions = $app->db->fetchAll();

            //hämta ut svaren
            $qtions = null;
            foreach ($questions as $qObj) {
                $q = $qObj->question;

                $qtions .= "$q <br/>";
            }

            $content .= "<h2> Senaste frågor: </h2> <br/>
                        $qtions <br/>
                        ";

            //Sedan tar jag fram en array över de olika taggarna
            //mina 5 taggar


            //beauty
            $app->db->select()
                     ->from('questions WHERE subject LIKE "%beauty%"');
            $app->db->execute();
            $btyTag = $app->db->fetchAll();


            //hämta ut beauty
                $beauty=[];
                $beauty[0] = "beauty";
            foreach ($btyTag as $tag) {
                $beauty[] = $tag->user;
            }

            //hämta ut tech
            $app->db->select()
                     ->from('questions WHERE subject LIKE "%technique%"');
            $app->db->execute();
            $techTag = $app->db->fetchAll();


                $tech=[];
                $tech[0] = "technique";
            foreach ($techTag as $tag) {
                $tech[] = $tag->user;
            }

            //hämta ut history
            $app->db->select()
                     ->from('questions WHERE subject LIKE "%history%"');
            $app->db->execute();
            $histTag = $app->db->fetchAll();


                $hist=[];
                $hist[0] = "history";
            foreach ($histTag as $tag) {
                $hist[] = $tag->user;
            }

            //hämta ut material
            $app->db->select()
                     ->from('questions WHERE subject LIKE "%material%"');
            $app->db->execute();
            $matTag = $app->db->fetchAll();


                $mat=[];
                $mat[0] = "material";
            foreach ($matTag as $tag) {
                $mat[] = $tag->user;
            }

            //hämta ut forms
            $app->db->select()
                     ->from('questions WHERE subject LIKE "%forms%"');
            $app->db->execute();
            $formsTag = $app->db->fetchAll();


                $form=[];
                $form[0] = "form";
            foreach ($formsTag as $tag) {
                $form[] = $tag->user;
            }

            $longest = max($beauty, $tech, $hist, $mat, $form);
            $favSub = $longest[0];

            //fortsättning på content
            $content .= "<h2> Mest populära 'tag' </h2> <br/> $favSub <br/><br/>";

            //sedan plocka fram ur session
            $activeU = $app->session->get('activeU', []);

            $content .= "<h2> Mest aktive användare </h2> <br/>
                    $activeU[0]; <br/><br/>
            ";
        }
    } else {
        $content = "You need to login !";
    }

    $title = "Välkommen";

    $app->theme->setTitle("Final proyect, by Ylva");
    $app->views->add('finale/page', [
    'content' => $content,
    'title' => $title
    ]);

});

$app->router->add('user', function () use ($app) {


    //kontroll ifall det finns en användare inloggad
    $usersArr = $app->session->get('user', []);

    if ($usersArr != null) {

            $uName = $usersArr[1];

        if ($usersArr[0] == null) {
                $content = "You need to login to see this site";
        } else {
                $content = "$uName is logged in. <br/> <br/>
                Som användare på sidan får du tillgång till att använda alla delarna !
                Klicka runt lite :)";
        }
    } else {
        $content = "You gotta log in !";
    }


    $title = "Användare";

    $app->theme->setTitle("Användare");
    $app->views->add('finale/page', [
    'content' => $content,
    'title' => $title
    ]);

});


//setup user
//prova lägga in route för databas
$app->router->add('setupUsers', function () use ($app) {

    //vebose kan stängas av o på beroende på om man vill se sql
        $app->db->setVerbose();

       $app->db->dropTableIfExists('user2')->execute();

       //Att skapa ett table sker såhär:
       $app->db->createTable(
           'user2',
           [
               'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
               'acronym' => ['varchar(20)', 'not null'],
               'email' => ['varchar(80)'],
               'name' => ['varchar(80)'],
               'picture' => ['varchar(255)'],
               'password' => ['varchar(255)']
           ]
       )->execute();

       //sedan prova att lägga in användare
       //det ser ut såhär
       $app->db->insert(
           'user2',
           ['acronym', 'email', 'name', 'picture', 'password']
       );



        $app->db->execute([
            'admin',
            'admin@dbwebb.se',
            'Administrator',
            'https://downloads.andyroid.net/website/v2//wp-content/uploads/2017/01/Spaceship-Battles-icon.png',
            password_hash('admin', PASSWORD_DEFAULT),
        ]);

        $app->db->execute([
            'doe',
            'doe@dbwebb.se',
            'John/Jane Doe',
            'http://www.publicdomainpictures.net/pictures/130000/velka/tiger-fractal-wire-flame.jpg',
            password_hash('doe', PASSWORD_DEFAULT),
        ]);

});

//dispatcher kopplingen till users2controller
//kopplingen till controllern user
$app->router->add('users2', function () use ($app) {

    $app->dispatcher->forward([
        'controller' => 'users2',
        'action'     => 'list',
    ]);

});



$app->router->add('questions', function () use ($app) {

    //kontroll ifall det finns en användare inloggad
    $usersArr = $app->session->get('user', []);

    if ($usersArr != null) {
        $uName = $usersArr[1];
        if ($usersArr[0] == null) {
            $content = "You need to login to see this site";
        } else {
            $content = "$uName is logged in <br/> <br/>
            Varsegod att undersöka frågeforumet";
        }
    } else {
        $content = "Log in please !";
    }

    $title = "Frågor";

    $app->theme->setTitle("Användare");
    $app->views->add('finale/page', [
    'content' => $content,
    'title' => $title
    ]);

});


//Setup questions
$app->router->add('setupQuestions', function () use ($app) {
    //vebose kan stängas av o på beroende på om man vill se sql
        $app->db->setVerbose();

       $app->db->dropTableIfExists('questions')->execute();

       //Att skapa ett table sker såhär:
       $app->db->createTable(
           'questions',
           [
               'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
               'user' => ['varchar(20)', 'not null'],
               'subject' => ['varchar(255)', 'not null'],
               'question' => ['text'],
               'timeCheck' => ['datetime']
           ]
       )->execute();

       //tiden nu
       $now = gmdate('Y-m-d H:i:s');

       //sedan prova att lägga in användare
       //det ser ut såhär

       //Vilket table & vad som ska sättas in
       $app->db->insert(
           'questions',
           ['user', 'subject', 'question', 'timeCheck']
       );

       //the parameters
        $app->db->execute([
            '1',
            'beauty',
            'Ska jag ha både halsband och örhängen på?',
            $now

        ]);

        $app->db->execute([
            '2',
            'technique',
            'Hur får silvret sin form?',
            $now

        ]);


});

//dispatcher kopplingen till questionController
//kopplingen till controllern user
$app->router->add('questions', function () use ($app) {

    $app->dispatcher->forward([
        'controller' => 'questions',
        'action'     => 'list',
    ]);

});

//Setup questions
$app->router->add('setupAnswers', function () use ($app) {
    //vebose kan stängas av o på beroende på om man vill se sql
        $app->db->setVerbose();

       $app->db->dropTableIfExists('answers')->execute();

       //Att skapa ett table sker såhär:
       $app->db->createTable(
           'answers',
           [
               'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
               'user' => ['integer', 'not null'],
               'question' => ['varchar(20)', 'not null'],
               'answer' => ['text']
           ]
       )->execute();

       //sedan prova att lägga in användare
       //det ser ut såhär

       //Vilket table & vad som ska sättas in
       $app->db->insert(
           'answers ',
           ['user', 'question', 'answer']
       );

       //the parameters
        $app->db->execute([
            '2',
            '1',
            'Jag tycker det beror dels på din egen smak, o dels på vilka typer av smycken som det är.'
        ]);

        $app->db->execute([
            '1',
            '2',
            'Silvret får sin form genom upphettning då det blir mjukare och formbart, och sedan en behandling.
            <br/> Det finns många olika behandlingar - såga, banka, hamra, laminera osv. <br/>
            Man kan ju hitta på egna sätt att ta fram former också, om man så vill. <br/>
            Ex med en annan metall med högre smältpunkt sätta i en form i silvret.',
        ]);


});

//dispatcher kopplingen till answersController
//kopplingen till controllern answer
$app->router->add('answers', function () use ($app) {

    $app->dispatcher->forward([
        'controller' => 'answers',
        'action'     => 'initialize',
    ]);

});

//Setup questions
$app->router->add('setupComments', function () use ($app) {
    //vebose kan stängas av o på beroende på om man vill se sql
        $app->db->setVerbose();

       $app->db->dropTableIfExists('comments3')->execute();

       //Att skapa ett table sker såhär:
       $app->db->createTable(
           'comments3',
           [
               'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
               'user' => ['integer', 'not null'],
               'question' => ['varchar(20)', 'not null'],
               'comment' => ['text']
           ]
       )->execute();

       //sedan prova att lägga in användare
       //det ser ut såhär

       //Vilket table & vad som ska sättas in
       $app->db->insert(
           'comments3',
           ['user', 'question', 'comment']
       );

       //the parameters
        $app->db->execute([
            '2',
            '1',
            'Det är ju kul att variera. Klä sig beroende på vad man vill förmedla den dagen,
            klä sig för att känna sig extra. Där är en sådan glädje i att klä sig och uttrycka med stil.'
        ]);

        $app->db->execute([
            '1',
            '2',
            'Människan formar ju silvret efter tycke. Men vad är egentligen silver?',
        ]);


});




//dispatcher kopplingen till commentsController
//kopplingen till controllern comments
$app->router->add('comments3', function () use ($app) {

    $app->dispatcher->forward([
        'controller' => 'comments3',
        'action'     => 'list',
    ]);

});



//Setup commentsOnComments

$app->router->add('setupCom', function () use ($app) {
    //vebose kan stängas av o på beroende på om man vill se sql
        $app->db->setVerbose();

       $app->db->dropTableIfExists('com')->execute();

       //Att skapa ett table sker såhär:
       $app->db->createTable(
           'com',
           [
               'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
               'user' => ['integer', 'not null'],
               'answer' => ['integer', 'not null'],
               'commentNew' => ['text']
           ]
       )->execute();

       //sedan prova att lägga in användare
       //det ser ut såhär

       //Vilket table & vad som ska sättas in
       $app->db->insert(
           'com',
           ['user', 'answer', 'commentNew']
       );

       //the parameters
        $app->db->execute([
            '1',
            '1',
            'Javisst, visa den som man är.'
        ]);

        $app->db->execute([
            '2',
            '2',
            'Det låter som att det finns stora möjligheter inom silversmide! ',
        ]);


});



//dispatcher kopplingen till comments on comments
//kopplingen till controllern comments
$app->router->add('com', function () use ($app) {

    $app->dispatcher->forward([
        'controller' => 'Com',
        'action'     => 'list',
    ]);

});





$app->router->add('tags', function () use ($app) {

    //kontroll ifall det finns en användare inloggad
    $usersArr = $app->session->get('user', []);

    if ($usersArr != null) {
        $uName = $usersArr[1];

        if ($usersArr[0] == null) {
            $content = "You need to login to see this site";
        } else {
            $content = "$uName is logged in <br/> <br/><br/>

            <h1> Välj ett tema : </h1>

            <a href='questions/showSubject/beauty' alt='beauty'> Skönhet </a>
            <a href='questions/showSubject/technique' alt='technique'> Teknik </a>
            <a href='questions/showSubject/history' alt='history'> History </a>
            <a href='questions/showSubject/material' alt='material'> Material </a>
            <a href='questions/showSubject/forms' alt='forms'> Forms </a>

            ";


        }
    } else {

        $content = "Log in to see the content";

    }


    $title = "Tags";

    $app->theme->setTitle("Användare");
    $app->views->add('finale/page', [
    'content' => $content,
    'title' => $title
    ]);

});

$app->router->add('about', function () use ($app) {

    //kontroll ifall det finns en användare inloggad
    $usersArr = $app->session->get('user', []);

    if ($usersArr != null) {
        $uName = $usersArr[1];

        if ($usersArr[0] == null) {
            $content = "You need to login to see this site";
        } else {
            $content = "$uName is logged in <br/> <br/>
            Denna sidan är en uppgift i kursen mvcphp ifrån bth, 2017 <br/>
            <br/>
            Jag som skapat sidan heter Ylva. <br/>
            Rättare sagt har jag lärt mig att arbeta i ett ramverk skapat av Mikael Roos. <br/>
            Det är som att använda en utbyggnadsbar mall. <br/>
            <br/>
            Jag tycker programmering är jätte intressant och vill fortsätta lära mig mer. <br/>
            Det är roligt på ett stimulerande sätt då man är på en passande nivå. <br/>
            <br/> I övrigt arbetar jag med metallsmide och smyckesdesign. <br/>
            Vilket jag också älskar.
            <br> <br/> Att vara upptagen med sina projekt mest hela tiden...
            <br/> känns spännande. <br/><br/><br/>
            ";
        }
    } else {
        $content = "You must log in first";
    }


    $title = "Användare";

    $app->theme->setTitle("Användare");
    $app->views->add('finale/page', [
    'content' => $content,
    'title' => $title
    ]);


});

$app->router->add('login', function () use ($app) {

    //formulär för att checka användare
    $form = "<form method='post' action='users2/login'>
                Akronym: <input type='text' name='acronym'> <br/>
                Lösenord: <input type='text' name='password'> <br/>
                <input type='submit' value='Logga in'>
            </form>";

    //formulär för att logga ut
    $form2 = "
    <form method='post' action='users2/logout'>
        <input type='submit' value='logout'>
    </form>
    ";


    $title = "Log in / Log out user";
    $app->views->add('finale/login', [
            'title' => $title,
            'form' => $form,
            'form2' => $form2
            ]);



    $app->theme->setTitle("About");
    //$app->views->add('me/me');
});

//Här hanteras de olika routrarna
$app->router->handle();

//En stil renders.
$app->theme->render();
