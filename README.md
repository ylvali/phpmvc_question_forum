# phpmvc_question_forum

Question forum in phpmvc - final project BTH course , in swedish


För att prova detta projekt så måste det finnas en fungerande Anax-phpmvc miljö. 
Då du har det går det bra att plocka in alla filer på sin plats. 
Jag har inte tagit med bilder, theme eller stil - enbart funktionalitet. 

Det krävs också en fungerande CDatabase, finns att ladda ner via composer.

Jag har skrivit en rapport där det går att följa mitt arbete igenom uppgiften - att skapa ett frågeforum med databasuppkoppling.
 Det finns också en hel del förklaringar löpande i koden.
Jag hänvisar till kursen mvcphp på BTH av Mikael Roos för att få en bättre uppfattning om ANAX mvc-php.
Med kunskap om det ramverket så syns det var dessa filerna passar in.


Rapporten :

1.	The framework

Okej, då börjar jag med att ladda upp min kopia av anax-mvcphp .
Den dependency jag behöver för att denna uppgift ska fungera är ’CDatabase’ av mos. Den går att ladda ner via composer.

Att injecera en dependency är som att ladda in en funktionslista .Framework är en sorts användbar mall som man själv kan bygga ut och använda. Den bygger dels på $di som är funktioner som återanvänds i olika projekt/appar och sedan själva objektet/appen/webbplatsen som har sitt unika sätt och utseende ’injicerad’ med $di.
1.1 The frontcontroller
webroot/finale.php
Jag sätter sedan in en ny frontcontroller – finale , kallar jag den.  Den lägger jag i min webroot. 
Frontcontrollern är den controllern som styr projektet.
Den hämtar info från ett ställe, presenterar den på ett annat. Sätter look på informationen – alltså ett ’tema’ -
tar har hand om kopplingen till konfigurationsfiler och setup.  Det är här som de olika delarna möts och blir ’the framework’ samt tankas med önskad information och funktion. Utan en frontcontroller blir det bara massa lösa filer. Nu finns en enhet.
Det är uppdelningen som gör projektramen återanvändbar. Vissa delar används ju i många olika typer av appar, i detta sorts system är önskan att bara behöva göra om det nödvändiga ... vilket leder till effektivitet och underhållbarhet. Detta är en stor styrka .

1.2 Konfigurationsfilen
Först så sätter jag in konfigurationsfilen .
Den ligger också i webroot. 
Webroot/config_with_app.php 
I min konfigurationsfil sker följande : 
-	$di skapas som ett objekt av CDIFactoryDefault() . (En klass  som tillhör anax-php och innehåller därmed funktioner att anropas ifrån hela projektet och ifrån allt som kopplas till $di.
-	$app skapas mha av $di via klassen CapplicationBasic som tar $di som argument. 
-	$di tankas med olika controllers .
-	config.php ...min konfigurationsfil innehåller också en koppling till anax konfigurationsfil .
Då man tankar in controllers i $di så gör man funktionsdokument tillgängliga ifrån applikationen och det som finnes i ramverket. Det är ett praktiskt och underhållbart sätt att förenkla en produktion av applikationer. Man kan också enkelt läsa in andras färdiga controllers och göra dem till en del av ens egna projekt. Effektivt och smart kan det verkligen bli. 
$app är i sin tur den applikation/webbsida som man arbetar på. Som tilltalas som ett objekt under projektets gång.

1.2 Navbar 
Sedan dags att lägga in en navbar . I frontcontrollern anges koden för att skapa navbaren. Här är anax-mvcphp väldigt snabbt och enkelt att använda. En linje kod som pekar på en fil i $app->config mappen. Navbar_finale.php heter min fil här och i den så hittas mallkoden för en navbar. Det är bara att applicera nytt info. Ett enkelt och snabbt sätt, man fyller helt enkelt i arrayer. Det blir sedan drop down listor. 
I listan så används alla de olika routers som man ska ha i frontcontrollern, dvs de olika innnehåll som ska visas i ramverket. Lite som sidorna i en tidning. Fast då alltså interaktiva, internet är så häftigt.

1.3 The theme 

Anax-mvc har ett standard theme, alltså ett utseende. Men då jag vill sätta min egen look på sidan så bestämmer jag det med en rad kod i frontcontrollern. En linje kod bara. Koden leder till configfilen i $app. 
Jag döper min sådan fil till theme_finale . 
Jag använder den mallen som finns genom anax, för enkelhetsskull. 
Den är en array med information. 
Den bygger på en uppdelning av sidan i header, footer – ett visuellt ramverk . 
Här anges också plats för navbaren. 
I theme är de olika sektionerna enkelt uppdelade så det går lett att uppdatera ex vilket traditions enligt css/less stylesheet som man vill ha, vilken favicon, länk till modernizer mm. 
Jag använder mig av style.php som är ett sätt att kombinera både css och less i ens projekt. 

1.4 The routers & vyer
Därefter skapar vi routsen ... dvs innehållet som visas i det visuella ramverket som vi nyss byggt upp. Det i ’rutan i mitten’ i detta fall. Varje ’route’ är som ett litet program som tar fram olik information ifrån ramverket och placerar ut i önskad ordning. Ex kan man ta fram en text ur en folder i ens $app och sedan via routern skriva ut den till användaren genom att använda en vy.
 En vy som syns ja. Ifrån routsen så skickas info i variabler, sedan kan man mycket enkelt placera ut variablerna där man vill ha dem - i text eller funktioner. 
Allting i routen sker via $app.

Handle & render
Frontcontrollern avslutas med en handle och render via $app. 
Så, då är grundstrukturen färdig. 

1.5 Sidans upplägg
Sidan byggs via routes upp i frontcontrollern. Det ska finnas en första sida där användaren börjar, en sida för frågor där det snyggt tas fram frågor ur databasen samt responsen ifrån användarna på dessa. Taggarna har i sintur en sida med klickbara länkar som också tar fram info ifrån databasen och visar för användren. Användare ska också listas ifrån databas. Hela denna rapport beskriver förloppet och sättet att nå målet . 

2.	Databasupkoppling
Databasuppkoppling sker via CDatabase som laddas in i $di. 
Den går att ladda ner via composer. Paketen som går att ladda ner via composer finns på packagist. Så kan man ta hem hela moduler och använda andras kreativitet och arbete , så smart och effektivt. 
I config filen tankas $di med CDatabase... jag gör detta i min $config_with_app . Sedan kan man i hela $app kommunicera med CDatabase och alltså den databas som finns som tillgång till projektet. Denna websida bygger till stor del på lagring , framtagning och kommunikation med en databas. Det gör innehållet dynamiskt och kommunikationen emellan och med användare möjlig. 

3.	Användare
Nu ska jag skapa användare. En användare får ett gäng med attribut, såsom namn , email etc. Denna samling med info kallar jag sedan för mina användare. Detta är exempel på ett objekt .
Användaren ska sparas i databasen i ett table : ’user2’ där den också ges ett unikt id.

3.1 Frontcontrollern och routen setupUsers
Först skapar jag ett table i databasen i froncontrollern via $app->db och arrayer som kommunicerar med Cdatabase och i sin tur sparas i själva databasen. Ett sätt att förenkla att inte använda så mång sql satser, kortare kommandon och återanvändbar kod. 
Nu räcker det med att efter uppladdningen använda den routen, dvs öppna den i queryfältet , så syns det att databasen laddas med användare, om verbose är aktiverat. 
Jag kontrollerar också i workbench att the table har laddats upp och att det nu existerar användare.

3.2 Modell för users
Jag har här anammat förslaget att använda model – class – controller som sätt att hantera användare i min databas. Modellen är kommunikationsobjektet emellan controllern och klasserna.
$app -> src -> user2 : Här placerar jag dels user2 modellen och dels user2 controllern. 
Namespace är här en styrande faktor och det är viktigt att ha rätt namespace. Jag skapar här ett nytt namespace som heter user2. 
CDatabasemodell används. User2 är en extension av denne, vilket betyder att vi använder oss av dess funktioner. Dessa är specialgjorda för att kommunicera med databasen , den kommunicear med det table i databasen som har samma namn som modellen, alltså User2 i detta fallet.
Jag gillar denna struktur .  

3.3 Controller för users2
Jag skapar en controller i User2, för funktioner som rör mina users2. Den använder \ANAX\DI\Tinjectable och injicerar senare $di i objekt som skapas.  Alltså, funktionslistor som använder sig av varandra. Lite som include ifrån föregående kurser , men nu ser det proffsigare ut. 
Det kommer alltså att skapas ett objekt varje gång controllers klassen används , objektet är av typen users2 och får därmed tillgång till databasuppkopplingen och kan använda alla funktionerna i denne controller klass.
Controller-klassen måste också tankas in i $di, och det gör jag i $config_with_app .
Namnsättningen är viktigt att den är korrekt . 
Sedan skapar jag en koppling via dispatch i frontcontrollern som sätter action ’list all’.  Det är en ’osynlig’ koppling där man skickas ifrån en controller till en annan. I controllern som anopas används sedan ’action’ på alla funktionsnamns avslut ... och det räcker med att ange ett annat namn i query fältet ( ex listAll) tillsammans med namnet på routen som dispatchar , så används korrekt funktion. Det är enkelt o smart när man fattat det. 
Anax-MVC/webroot/finale.php/questions
I controllern ser jag sedan till att utföra det som jag vill genom olika funktioner. 
-	Visa alla användare
-	Lägga till användare 
-	Uppdatera användare
Uppdatera användare görs nästan på samma sätt som skapandet av en användare. Principen är densamma – ett formulär som registreras i en databas med de uppgifter som sammanställer ’användaren’. Skillnaden är att det redan finns en sådan användare sparas och det är de befintliga uppgifterna som tas fram. Jag tar fram dessa uppgifterna direkt i formuläret som användaren ska fylla i via $value. 

3.4 Bilden 
Okej, så bilden ska vara en gravatar. Det betyder att det är en liten bild som syns brevid namnet –avatar – som dessutom är global ... alltså en gravatar. Den syns då man besöker en ’gravatar enabled site’. 
Det var enkelt att enligt anvisningarna på gravatars sida göra en gravatar (hittad via google) . Jag lade in funktionen för att visa användare. 

3.5 Overview of users
Jag tänker mig en overview av users, där man kan välja väg. Detta sker i navbaren.
-	Se vilka användare det finns  -> öppna olika användares sidor  
-	Skapa en användare 
-	Se mest aktiva användare

Se mest aktiva användare
För att se mest aktiva användare så måste all aktivitet i form av inlägg läggas samman. 
Dvs : antal frågor + antal svar + antal kommentarer. 
För varje användare det finns så hämtar jag antal inlägg i varje aktivitet och lägger samman.

4.	Lösenordsskydda
Sidan ska lösenordsskyddas.
När det finns användare så går det bra att göra ett lösenordsskydd. Användarna finns ju nu registrerade i databasen. Kontroll via formulär och databas, ifall det är en match så loggas användaren in. Inloggningen ska sparas i sessionen. Så kan också sessionen kontrolleras varje gång som en användare vill ta sig in i systemet, ifall det inte finns någon användare sparad, så krävs en ny inloggning.

Då användaren inte är inloggad så går det inte att se några sidor utan enbart en uppmaning om att man måste logga in. 
Först ska jag skapa en sida för login/logout kopplat till session. 
Sedan ska jag lägga en sessionskontroll i frontcontrollern.

4.1 Login/Logout
Login och logout är alltså kopplat dels till databas och dels till sessionen. Via ett formulär så kontrolleras en akronym och ett lösenord. Ifall det finns en motsvarande användare, så blir användaren inloggad och detta sparas i sessionen. Ifall det inte finns någon sådan användare så returneras istället det till användaren som försökt att logga in. 
Jag lägger till en ny route för login och lägger till ’login’ alternativet i navbaren. 
Via view skickar jag ett formulär till användaren , formuläret använder ’post’ och skickar i sin tur vidare till Users2Controllern via dispatch, samt till en action som heter login. 
I login så testas det hashsparade lösenordet , akronymen och en respons ges till användaren. Har användaren fyllt i korrekt användarnamn samt lösenord så sparas inloggningen i sessionen ’user’.    Det är id på user som sparas. O så var användaren ’inloggad’.

4.2 Frontcontrollern
I frontcontrollern läser jag sedan in en sessionscontroll. Finns det en användare inloggad så presenteras informationen, om inte så hänvisas till loginsidan. Jag gillar att detta sker i fontcontrollern, det känns säkert. 
4.3 I övriga controllers
I övriga controllers måste också det göras en lösenordskontroll innan infomation skrivs ut. 

5.	The questions 
Jag tänker mig att frågedelen byggs på samma sätt – en modell, en controller och en koppling till databasen. Varje fråga ska ha ett id, en användare, ett ämne samt en fråga.
Jag tänkte först göra en egen tabell för ämnena... alltså ’tagsen’. 
Men sedan tänkte jag att det räcker med skilda ord att ha i varchar. Man kan ha säg max 5 ämnen per fråga. De ska läsas in som en array och sedan göras till en string att lagra i databasen. De läses in via checkboxes.
Man kan sedan hämta ut vilka frågor som tillhör en specifik tag med %...% i sql.  Det betyder att strängen inneåller det specifika ordet .
Efter varje fråga så kontrolleras ifall det finns något svar / kommentar registrerat till den frågan. Ifall det finns de så ska det skrivas ut under frågan.

Det finns också möjlighet att svara eller kommentera på frågorna.
Jag tycker att sidan framförallt byggs kring denna QuestionControllern. Jag beskriver lite mer ingående hur jag gått tillväga.

5.1 Utförande 
I frontcontrollern så gör jag en setup för frågornas table i databasen.
Sedan skapar jag en modell av questions som bygger på CDatabaseModel , samt en question-controller i namespace ’question’ . Jag öppnar sedan min $config fil där jag tankar in controllern i $di och kollar att den fungerar önskvärt. 
Sedan skapar jag vägen i frontcontrollern... alltså kopplingen till controllern, så att man sedan enkelt kan anropa den och dess funktioner via queryfältet. Dispatch alltså.
I controllern skapar jag också kontakt med användar table och hämtar ut namn på den som ställt frågan utifrån frågans obligatoriska id. Så blir det trevligare att se än bara ett nummer.
Detta skrivs ut i en vy – fråga och vem som ställt frågan. Här ska det då vara klickbart att skriva ett svar på frågan. Då användaren klickar så öppnas en ny vy med möjlighet att se frågan och att svara. Svaret registreras automatiskt i databasen för svar och således visas också automatiskt i relation till sin fråga.
Det ska också registreras vilken tid som en specifik fråga skapades. Sedan kan man ta fram de tre senaste inläggen i databasen genom att använda sortering.  Jag gör en speciell funktion för att se de tre senaste inläggen i forumet. 
Det ska också gå att lägga till en ny fråga – alltså att registrera användarens uppgifter och fråga och spara i databasen som nu redan automatiskt tas fram.

6.
The answers
Jag gör då så att jag gör en funktion i questionControllern som tar emot ett id som argument, id på fråga alltså, och öppnar därmed en ny vy med ett formulär att svara på frågan. Detta nås enkelt via en länk som skickar användaren vidare via query fältet. 
Alla svar måste ha en fråga, en användare och ett svar. 
Jag måste också göra en setup på table i databasen, och som vanligt så gör jag det då i frontcontrollern.
Jag lägger också in två svar i setupen. 
Jag fortsätter i samma mönster som tidigare och gör en controller specifikt för svar – ’answerController’ . Jag skapar en modell för’answers’ objekt som kan använda controllern, och den bygger på CdatabasModel .  Jag tankar också $di med den funktionen. O slutligen så lägger jag in vägen i frontcontrollern. Sedan kan jag börja använda modellen.  Vilket betyder att jag enkelt och snyggt kan kommunicera med databasen.
Svaren ska skrivas ut under varje fråga. Så i ’list’ funktionen i controllern för questions så gör jag också en koppling till table ’questions’ som då hämtar ut alla svar med motsvarande id. Sedan skrivs de ut under frågan i en ruta. 

7.
The comments
Kommentarer gör jag precis på samma sätt som med svaren, fast med comments3Controller (då det finns andra commentControllers i mitt system som jag inte vill använda nu). O alltså – en modell som bygger på databasklassen, läsa in controllern i $di. Setup för databasen i frontcontrollern ... o varje kommentar har en användare och en fråga. 
I routen sätts dispatch->forward och sedan är controllern redo att användas. Jag skriver de funktioner jag vill ha  controllern och sidan gör som jag önskar.
Hurusom, då kopplingarna är gjorda, är det främst i questioncontroller som jag tar fram info via objektet, då kommentarerna ska presenteras tillsammans med sin fråga. Som sagt så är questionControllern central i detta projekt.
Sedan lägger jag till en länk att klicka på ”kommentera” under frågan brevid ”svara” . Denna leder till controllern som sedan ger användaren ett formulär,  vars kommentar sparas i databasen och tas fram med sin fråga.
Det ska också gå att göra en kommentar på ett svar. 
Jag gör ett eget table för att kommentera på svar.  Därmed också en modell, en controller, en dispatch och en setup. 

8. The tags 
The tags är ämnena. Jag tänker inte göra något table för dem, utan enbart bestämma 5  ämnen som berör smycken. 
Ämnena är klickbara och tar fram vilka frågor som finns registrerade i table för specifik question.  
Det är en funktion i ’questions’ som tar in en parameter med namn på sökt tag (subject har jag använt som namn i tabellen) och tar sedan fram alla frågor som innehåller specifikt namn.
