<?php

namespace Anax\Users2;

/**
 * A controller for users2 and admin related events.
 *
 */
class Users2Controller implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->user2 = new \Anax\Users2\User2(); //skapa ett objekt
        $this->user2->setDI($this->di); //injektar $di i objektet

        //questions
        $this->questions = new \Anax\Questions\Questions(); //skapa ett objekt
        $this->questions->setDI($this->di); //injektar $di i objektet

        //answers
        $this->answers = new \Anax\Answers\Answers(); //skapa ett objekt
        $this->answers->setDI($this->di); //injektar $di i objektet

        //comments
        $this->comments = new \Anax\Comments3\Comments3(); //skapa ett objekt
        $this->comments->setDI($this->di); //injektar $di i objektet

        $this->com = new \Anax\Com\Com(); //skapa ett objekt
        $this->com->setDI($this->di); //injektar $di i objektet

        //anropa active user funktionen
        $this->activeUser();
    }


    /**
 * List all users.
 *
 * @return void
 */
    public function listAction()
    {
            $theUsers = $this->user2->findAll(); //metoden 'findAll()' hämtar alla users
            $userInfo = "";


            //en foreach sats hämtar ut users och skriver ut snyggare
            //ger också möjighet att klicka sig vidare till uppdatering
            //av den specifika användaren

        foreach ($theUsers as $object) {
                $name = $object ->name;
                $picture = $object->picture;
                $id = $object->id;
                $acronym = $object->acronym;
                $email = $object->email;

                //creating a gravatar
                $size = 20;
                //$default = "http://www.publicdomainpictures.net/pictures/130000/velka/tiger-fractal-wire-flame.jpg";
                $gravatar =   "https://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($picture)."&s=".$size;


                $userInfo .= "
                            <div class='leftBox'>
                                <hr/>
                                <img src=$gravatar width=300 alt='gravatar'>

                                <br/>
                                Id: $id <br/>
                                Name: $name <br/>
                                Acronym: $acronym <br/>
                                Email: $email <br/>

                                <br/>
                                <br/>
                                <a href='listOne/$id' alt='see one'> >Öppna sida< </a>
                                <br/>
                                <br/>
                                <hr/>


                            </div>
                            <br/>
                            <br/>
                            ";
        }

            //kontrollera så att användaren är inloggad
            $usersArr = $this->session->get('user', []);

        if ($usersArr != null) {
                $uName = $usersArr[1];
            if ($usersArr[0] == null) {
                    $content = "You need to login to see this site";
            } else {
                    $content = $userInfo;
            }
        } else {
                $content = "Log in please !";
        }

            //Lämnar över till vyn att skriva ut alla users
            $this->theme->setTitle("List all users");
            $this->views->add('users2/list-all', [
                'users' => $content,
                'title' => "Användarna",
            ]);
    }



    //En funktion att enbart visa en enskild användares sida.

    public function listOneAction($aId = null)
    {

        //kontrollera så att användaren är inloggad
        $usersArr = $this->session->get('user', []);

        if ($usersArr != null) {
                $uName = $usersArr[1];
            if ($usersArr[0] == null) {
                    $content = "You need to login to see this site";
            } else {


                    //hämta ut användar objektet
                        $usr = $this->user2->query()
                                ->where('id = ?')
                                ->execute([$aId]);


                            $nameAnswer = null;
                            $userInfo = null;
                foreach ($usr as $obj) {
                            $name = $obj ->name;
                            $picture = $obj->picture;
                            $id = $obj->id;
                            $acronym = $obj->acronym;
                            $email = $obj->email;

                            //creating a gravatar
                            $size = 20;
                            //$default = "http://www.publicdomainpictures.net/pictures/130000/velka/tiger-fractal-wire-flame.jpg";
                            $gravatar =   "https://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($picture)."&s=".$size;


                            $userInfo .= "
                                        <div class='leftBox'>
                                            <hr/>
                                            <img src=$gravatar width=300 alt='gravatar'>

                                            <br/>
                                            Id: $id <br/>
                                            Name: $name <br/>
                                            Acronym: $acronym <br/>
                                            Email: $email <br/>

                                            <br/>
                                            <a href='../update/$id' alt='update'> Uppdatera här </a>
                                            <hr/>

                                        </div>
                                        <br/>
                                        <br/>
                                        ";
                }

                //ta också fram vilka frågor / svar / kommentarer som användaren gjort
                //hämta ut användar objektet
                    $qTions = $this->questions->query()
                            ->where('user = ?')
                            ->execute([$id]);

                    $nSers = $this->answers->query()
                            ->where('user = ?')
                            ->execute([$id]);


                    //Hämta ut info ifrån frågeobjektet
                    $QuId = null;
                    $QuQuest = null;
                foreach ($qTions as $Qobj) {
                        $QuId = $Qobj->id;
                        $QuQuest = $Qobj->question;
                }
                    //lägg till i utsktifts variablen
                    $userInfo .= " <h2>Ställda frågor:</h2> <br/>
                     Frågans id: $QuId  <br/>
                    $QuQuest <br/> <br/>";

                    //Hämta ut info ifrån answers variablen
                    $AnId = null;
                    $AnAnsw = null;
                    $anQtion = null;
                foreach ($nSers as $Aobj) {
                        $AnId = $Aobj->id;
                        $AnAnsw = $Aobj->answer;
                        $anQtion = $Aobj->question;
                }
                    //lägg till i utsktifts variablen
                    $userInfo .= " <h2>Gjorda svar:</h2> <br/>
                     Svarets id: $AnId  <br/>
                     Frågans id : $anQtion <br/>
                    $AnAnsw <br/> <br/>";



                        $content = $userInfo;
            }
        } else {
                    $content = "Log in please !";
        }

                //Lämnar över till vyn att skriva ut alla users
                $this->theme->setTitle("List all users");
                $this->views->add('users2/list-all', [
                    'users' => $content,
                    'title' => "Användarna",
                ]);

    }




    //En funktion för att lägga till användare
    //Uppdelad i 2 delar (2 funktioner)...
    //1. skapa ett formulär som skickas till användaren
    //2. spara ändringarna i databasen

    public function addAction()
    {

                //Skapa ett formulär
                $form = "
                    <form method='post' action='add2'>
                        Namn:<input type='text' name='name'><br/><br/>
                        Akronym:<input type='text' name='acro'><br/><br/>
                        Epost:<input type='text' name='email'><br/><br/>
                        Password:<input type='text' name='password'><br/><br/>
                        Bild:<input type='text' name='picture'><br/><br/>
                        <input type='submit' value='Lägg till användare'>
                    </form>
                ";

                //kontrollera så att användaren är inloggad
                $usersArr = $this->session->get('user', []);

        if ($usersArr != null) {
                    $uName = $usersArr[1];
            if ($usersArr[0] == null) {
                        $content = "You need to login to see this site";
            } else {
                        $content = $form;
            }
        } else {
                    $content = "Log in please !";
        }


                //Lämnar över till vyn att skriva ut alla users
                $this->theme->setTitle("Lägg till användare");
                $this->views->add('users2/list-all', [
                    'users' => $content,
                    'title' => "Lägg till ny användare",
                ]);
    }


    //sedan kommer infon tillbaka via $_POST och formulär,
    //ifrån användaren. Nu ska den infon sparas.

    public function add2Action()
    {

        $name = $_POST['name'] ? $_POST['name'] :"-";
        $acro= $_POST['acro'] ? $_POST['acro'] : "-";
        $email = $_POST['email'] ? $_POST['email'] :"-";
        $password = $_POST['password'] ? $_POST['password'] : "-";
        $picture = $_POST['picture'] ? $_POST['picture'] : "-";


                $this->user2->save([
                'acronym' => $acro,
                'email' => $email,
                'name' => $name,
                'picture' => $picture,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                ]);

    //creating a gravatar
                $size = 20;

                $gravatar =   "https://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($picture)."&s=".$size;



    //Lämnar över till vyn att skriva ut alla users
                $this->theme->setTitle("Tillagd användare");
                $this->views->add('users2/added', [
                'name' => $name,
                'acronym' => $acro,
                'email' => $email,
                'picture' => $gravatar,
                'title' => "Tillagd användare",
                ]);

    }

    // Man ska kunna uppdatera användare
    // Jag gör det här i 3 olika steg.

    // Denna funktion tar emot ett id för att kunna uppdatera en användare.
    // Sedan skickas id:t via post till nästa funktion.
    //Där kontrolleras ifall det finns en användare,
    //och om det finns en sådan skickas ett formulär tillbaka
    //med uppgifter och möjlighet till uppdatering.

    public function updateAction()
    {

        $form = "<form method='post' action='update2'>
        ID: <input type='text' name='id'>
        <input type='submit' value='choose id' name='submit'>";

        //kontrollera så att användaren är inloggad
        $usersArr = $this->session->get('user', []);

        if ($usersArr != null) {
            $uName = $usersArr[1];
            if ($usersArr[0] == null) {
                $content = "You need to login to see this site";
            } else {
                $content = $form;
            }
        } else {
            $content = "Log in please !";
        }

        $this->theme->setTitle("Uppdatera användare");
        $this->views->add('users2/update', [
            'form' => $content,
            'title' => "Uppdatera användare",
            ]);

    }

    //En funktion som uppdaterar information i databasen

    public function update2Action()
    {

        //hämta id ifrån det ifyllda formuläret som
        //skickar användaren hit

        $id = $_POST['id'];

        //kommunicera via objektet -> via databasen
        //kontrollera ifall det finns info för en användare
        $checkId = $this->user2->query()
            ->where('id = ?')
            ->execute([$id]);

        //hämtar ut info ifrån hämtat objekt,
        //om det finns något
            $name = null;
            $email = null;
            $acronym = null;
            $password = null;
            $picture = null;


        foreach ($checkId as $uInfo) {
            $name = $uInfo->name;
            $email = $uInfo ->email;
            $acronym = $uInfo ->acronym;
            $password = $uInfo ->password;
            $picture = $uInfo ->picture;
        }

            //skickar ett formulär att fylla i till användaren
            //alt skickar info att en sådan användare inte finns
        if ($name == null) {
                $info = "No user registered";
        } else {


                $info = "
                Id: $id; <br/>
                <form method='post' action='updateNow'>
                <input type='hidden' value='$id' name='id'>
                Name:<input type='text' value='$name' name='name'><br/>
                Email:<input type='text' value='$email' name='email'><br/>
                Acronym:<input type='text' value='$acronym' name='acronym'><br/>
                Picture:<input type='text' value='$picture' name='picture'><br/>
                <input type='submit' name='submit' value='Uppdatera'>
                ";

        }

            $this->theme->setTitle("Uppdatera användare");
            $this->views->add('users2/update2', [
                'feedback' => $info,
                'title' => "Uppdatera användare",
                ]);
    }

    //här tar programet emot parametrar för att spara i databasen

    public function updateNowAction()
    {

        // ta emot inkommande parametrar
        $id = $_POST['id'] ;
        $name = $_POST['name'] ? $_POST['name'] : "-";
        $email = $_POST['email'] ? $_POST['email'] : "-";
        $acronym = $_POST['acronym'] ? $_POST['acronym'] : "-";
        $picture = $_POST['picture'] ? $_POST['picture'] : "-";

        //spara i databasen
        $this->user2->save([
        'id' => $id,
        'acronym' => $acronym,
        'email' => $email,
        'name' => $name,
        'picture' => $picture,
        //'password' => $password,
        ]);




    //informera användaren
        $info = "Användaren har uppdaterats! ";
        $this->theme->setTitle("Uppdatera användare");
        $this->views->add('users2/update2', [
            'feedback' => $info,
            'title' => "Uppdatera användare",

            ]);

    }

    //en funktion att hantera logins
    public function loginAction()
    {
        //hämta in parametrarna ifrån formuläret
        $acronym = $_POST['acronym'];
        $password1 = $_POST['password'];

        //hämta ut ev användare
        $user = $this->user2->query()
            ->where('acronym = ?')
            ->execute([$acronym]);

        //hämta ut info ifrån objektet
            $name = null;
        foreach ($user as $obj) {
            $id = $obj->id;
            $name = $obj->name;
            $password2 = $obj->password;
        }

        //kontrollera ifall det finns en användare
        if ($name == null) {
                $user = "there is no user";
        } else {
                //kontrollera ifall lösenordet är korrekt
            if (password_verify($password1, $password2)) {

                    //spara i session
                    $uSess = $this->session->get('user', []);
                    $uSess[0] = $id;
                    $uSess[1] = $name;
                    $this->session->set('user', $uSess);


                    $user = "Välkommen $name!";


            } else {
                    $user = "$name , fel lösenord <br/> $password1 - $";
            }

        }


            $this->theme->setTitle("Login");
            $this->views->add('finale/login2', [
                'user' => $user
                ]);
    }

    public function logoutAction()
    {

        //töm session
        $uSess = $this->session->get('user', []);
        $uSess[0] = null;
        $uSess[1] = null;
        $this->session->set('user', $uSess);

        $user = "User logged out";

        //
        $this->theme->setTitle("Login");
        $this->views->add('finale/login2', [
            'user' => $user
            ]);
    }


    //en funktion som hämtar ut mest aktiv användare
    public function activeUserAction()
    {

        //först hämta ut antal användare totalt
        //$theUsers = $this->user2->findAll();

        //hämta ut id på alla de som gjort frågor
        $usrObj = $this->questions->query('user')
                    //->where('id = ?')
                    ->execute();
        //hämta idn på alla som gjort svar
        $usrObjAnsw = $this->answers->query('user')
                    //->where('id = ?')
                    ->execute();

        //hämta ut idn på alla de som gjort kommentarer
        $usrObjCom = $this->comments->query('user')
                    //->where('id = ?')
                    ->execute();

        //hämta ut idn på alla de som gjort kommentarer
        $usrObjCom2 = $this->com->query('user')
                    //->where('id = ?')
                    ->execute();


        //hämta ut alla idn
            $qtionArr = [];
        foreach ($usrObj as $user) {
            $qtionArr[] = $user->user;
        }
        foreach ($usrObjAnsw as $user) {
            $qtionArr[] = $user->user;
        }
        foreach ($usrObjCom as $user) {
            $qtionArr[] = $user->user;
        }
        foreach ($usrObjCom2 as $user) {
            $qtionArr[] = $user->user;
        }


        //tar fram antal ggr som användare gjort en fråga
        $arr = array_count_values($qtionArr);

        //tar fram vilken användare som gjort flest inlägg
        $max = array_keys($arr, max($arr));

        $mostActive = $max[0];

        //hämta ut användar objektet
            $usrA = $this->user2->query()
                    ->where('id = ?')
                    ->execute([$mostActive]);

        foreach ($usrA as $obj) {
            $nameA = $obj->name;
            $nameId = $obj->id;
        }

        $output = "The most active user is $nameA !!! <br/>
        <a href='listOne/$nameId' alt='mostActive'> Besök sidan </a>";

        //spara i session
        $activeU = $this->session->get('activeU', []);
        $activeU[0] = $nameA;
        $this->session->set('activeU', $activeU);


        $this->theme->setTitle("Login");
        $this->views->add('finale/usr', [
            'info' => $output

            ]);
    }

    //denna funktionen används för att kontrollera och spara mest akriva användare
    //det sparas i en session och tas fram på första sidan
    public function activeUser() {

            //först hämta ut antal användare totalt
            //$theUsers = $this->user2->findAll();

            //hämta ut id på alla de som gjort frågor
            $usrObj = $this->questions->query('user')
                        //->where('id = ?')
                        ->execute();
            //hämta idn på alla som gjort svar
            $usrObjAnsw = $this->answers->query('user')
                        //->where('id = ?')
                        ->execute();

            //hämta ut idn på alla de som gjort kommentarer
            $usrObjCom = $this->comments->query('user')
                        //->where('id = ?')
                        ->execute();

            //hämta ut idn på alla de som gjort kommentarer
            $usrObjCom2 = $this->com->query('user')
                        //->where('id = ?')
                        ->execute();


            //hämta ut alla idn
                $qtionArr = [];
        foreach ($usrObj as $user) {
                $qtionArr[] = $user->user;
        }
        foreach ($usrObjAnsw as $user) {
                $qtionArr[] = $user->user;
        }
        foreach ($usrObjCom as $user) {
                $qtionArr[] = $user->user;
        }
        foreach ($usrObjCom2 as $user) {
                $qtionArr[] = $user->user;
       }


            //tar fram antal ggr som användare gjort en fråga
            $arr = array_count_values($qtionArr);

            //tar fram vilken användare som gjort flest inlägg
            $max = array_keys($arr, max($arr));

            $mostActive = $max[0];

            //hämta ut användar objektet
                $usrA = $this->user2->query()
                        ->where('id = ?')
                        ->execute([$mostActive]);

        foreach ($usrA as $obj) {
                $nameA = $obj->name;
                $nameId = $obj->id;
        }

            $output = "The most active user is $nameA !!! <br/>
            <a href='listOne/$nameId' alt='mostActive'> Besök sidan </a>";

            //spara i session
            $activeU = $this->session->get('activeU', []);
            $activeU[0] = $nameA;
            $this->session->set('activeU', $activeU);

    }
}
