<?php

function processPost($post) {

    $username = htmlspecialchars(trim($post['username']));
    $password = htmlspecialchars(trim($post['password']));
    $email =    htmlspecialchars(trim($post['email']));

    if(strlen($username) < 3 || strlen($password)<7 || strlen($email) < 6) {
        return "Nevhodné uživatelské jméno, email nebo příliš krátké heslo (min 6 znaků).";
    }

    $options = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $dsn = "mysql:host=".getenv('DB_HOST').";dbname=auth;charset=utf8mb4";
    try {
        $pdo = new \PDO($dsn, getenv('DB_USERNAME'), getenv('DB_PASSWORD'), $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }

    $statement = $pdo->prepare("SELECT count(*) FROM account WHERE username = ?");
    $statement->execute([$username]);
    $accountsCount = (int) $statement->fetchColumn(); 

    if($accountsCount != 0) {
        return "Uživatel s tímto jménem již existuje.";
    }

    $sql = 'INSERT INTO account (username, sha_pass_hash, email) VALUES (?,?,?)';
    $pdo->prepare($sql)->execute([
        $username,
        sha1(strtoupper($username.':'.$password)),
        $email
    ]);

    return "Účet byl založen.";
    
}

if($_POST) {
    $message = processPost($_POST);
} else {
    $message = "";
}

?>
<!DOCTYPE html>

<html>

    <head>
        <meta charset="UTF-8">
        <title>Hearthglen RP Server</title>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap&subset=latin-ext" rel="stylesheet"> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>

    <body>
   
        <div class="container">

            <div class="row">

                <div class="col-12 header">
                    <h1>Hearthglen RP Server</h1>
                    <a href="https://discord.gg/zpqxNKq">Discord</a> | set realmlist 62.171.144.185
                </div>
            
                <div class="col-12">
                    <h2>Registrace herního účtu</h2>

                    <form method="POST" action="/">

                    <div class="form-group">
                        <label for="username">Uživatelské jméno</label>
                        <input id="username" name="username" type="text" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label for="email">Emailová adresa</label>
                        <input id="email" name="email" type="text" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label for="password">Heslo</label>
                        <input id="password" name="password" type="password" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Heslo pro ověření</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control"/>
                    </div>

                    <div class="form-group">
                        <input id="submit" name="submit" type="submit" class="form-control" value="Vytvořit účet"/>
                    </div>

                    </form>

                    <strong><?php echo $message ? $message : ""; ?></strong>
                </div>

                <div class="col-12">
                    <h2>O serveru</h2>
                    <p>Tento server vznikl zcela spontánně a bez rozmyslu s cílem poskytnout prostor všem hráčům, 
                    kteří si chtějí zahrát v tom nejklasičtějším settingu českých WoW RP serverů. Při jeho vzniku 
                    bylo vynaloženo jen to nejnutnější úsilí a rozhodně nemá ambici nahradit dlouho vyvýjené 
                    a promyšlené projekty. Jde jen o místo, kam můžete přijít, chvíli si zahrát a zas jít dělat 
                    něco užitečnějšího.</p>
                    <p>Nečekejte žádné technické vymoženosti. Přespawněná lokace kolem Hearthglenu, itemy bez statů 
                    (dostupné zdarma nebo nebo skoro zdarma), pár příšer v lesích na jihu, jasný nepřítel v podobě 
                    nemrtvých a téměř stoprocentní jistota, že pokud budete dodržovat níže psaná pravidla, nebude 
                    vás otravovat žádné GM s tím, že máte RPit rýmu.</p>
                </div>

                <div class="col-12">
                    <h2>Lore</h2>
                    <p>Příběh se odehrává během války s Králem Lichů na Northrendu.
                        Armáda Stříbrné Výpravy zaútočila na Northrend, spojila se s Aliancí i Hordou a dokonce i s Rytíři Smrti z Acherusu v posledním útoku na Citadelu Ledové Koruny, pod názvem Popelavý Verdikt. Tirion Fordring, šampion Světla a Ashbringer, vedl útok na Citadelu společně s hrdiny ze všech frakcí, probojoval se skrze šampiony Pohromy a utkal se s Králem Lichů na vrcholu Ledové Koruny.
                        A zde nastal osudový moment, kdy Arthas uvěznil Tiriona v ledovém sevření, zabil šampiony Azerothu a zvedl Mrazivý Smutek, aby před Tirionovýma očima vzkřísil šampiony jakožto generály Pohromy.
                        Tirion žádal Světlo o pomoc, aby ho osvobodilo z ledového sevření a dalo mu sílu Krále Lichů porazit.
                        </p><p>
                        Ale pomoc nepřišla. Světlo nebylo dost silné, aby prozářilo temnotu Citadely a pomohlo Tirionovi zvítězit. Král Lichů vzkřísil šampiony Azerothu jako své nové generály. Co se stalo s Tirionem, to už nikdo neví.
                        Šampioni se obrátili proti armádě Popelavého Verdiktu a rozbili obléhání citadely. Nemrtvé armády pod novým velením a zvýšenou silou vyhnaly veškeré útočníky z celé Ledové Koruny, kde se armáda Azerothu rozpadla na několik částí.
                        </p><p>
                        Náše část světa jest v Hearthglenu, nové državě Stříbrné Výpravy. Sem se Stříbrná Výprava vrátila po své porážce v Northrendu, vyhnala zbytky Šarlatové Výpravy a vybudovala město schopné ubytovat mnohé uprchlíky ze všech koutů Lordaeronu. Pro tento účel byla i západní část Morových Zemí očištěna paladiny z Výpravy a druidy z Cenariova Kruhu.
                        </p><p>
                        A zde provede Výprava poslední obranu proti masivní invazi, která se řítí z Northrendu.</p>
                </div>

                <div class="col-12">
                    <h2>Pravidla serveru</h2>
                    <ol>
                        <li>Chovejte se jako dospělí lidé.</li>
                        <li>Nikoho neurážejte, nenadávejte si, neveďte rasistické a jiné dementní řeči.</li>
                        <li>Buďte tolerantní. Pokud se vám něčí RP nelíbí, jděte jinam a třeba si o tom v klidu promluvte.</li>
                        <li>Mějte na paměti, že jste na RP serveru, omezte OOC pohyb postavy na minimum.</li>
                        <li>Pokud provádíte cokoliv mládeži nepřístupného, dělejte to tak, ať tím neotravujete nikoho jiného.</li>
                        <li>Neopouštějte Western Plaguelands.</li>
                        <li>Hrajte rozumně silné / významné postavy.</li>
                        <li>Najdete-li nějaký bug, který by si zasloužil opravu, oznamte nám to na Discordu.</li>
                    </ol>
                </div>

                <div class="col-12">
                    <h2>Vývoj</h2>
                    <p>Chcete na serveru něco vytvořit? Nápad, co chcete zrealizovat? Přístup k databázi, 
                        ke spawnu? Přihlaste se někomu z týmu na Discordu a určitě se domluvíme, je to z 
                        velké části otevřený komunitní projekt.</p>
                </div>

            </div>

        </div>

    </body>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</html> 