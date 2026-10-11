# SportRent – požičovňa športového vybavenia

Matej Kováč 

Študijná skupina: 5ZYI35

## Stručný opis projektu
SportRent je webová aplikácia pre malú požičovňu športového vybavenia (bicykle, lyže, snowboardy, bežecké a turistické vybavenie, kempingové potreby). Rieši problém, keď sa dostupnosť vybavenia a rezervácie evidujú ručne, v telefonátoch alebo v tabuľkách, a zákazník nemá prehľad o tom, čo je v ktorom termíne voľné.

Zákazník si v aplikácii prezrie katalóg, vyfiltruje vhodné vybavenie a vytvorí rezerváciu na zvolený termín. Správca požičovne spravuje ponuku vrátane fotografií, schvaľuje alebo zamieta rezervácie a eviduje vrátenie vybavenia. Aplikácia je určená zákazníkom požičovne a jej zamestnancom.

## Role v projekte
- **Návštevník (neprihlásený)** – môže si prezerať katalóg vybavenia, vyhľadávať a filtrovať ho, zobraziť detail položky vrátane fotografií a hodnotení. Môže sa zaregistrovať a prihlásiť. Nemôže vytvárať rezervácie.
- **Zákazník (registrovaný používateľ)** – má všetky práva návštevníka. Navyše vytvára, zobrazuje a ruší vlastné rezervácie, hodnotí vypožičané vybavenie a spravuje svoj profil.
- **Správca (admin)** – zodpovedá za obsah a prevádzku. Spravuje kategórie a vybavenie vrátane nahrávania fotografií, schvaľuje alebo zamieta rezervácie, označuje vrátenie vybavenia, moderuje hodnotenia a spravuje používateľov a ich roly.

## Prípady použitia podľa rolí
**Návštevník**
- prezerá katalóg vybavenia so stránkovaním,
- filtruje vybavenie podľa kategórie, ceny a dostupnosti v termíne,
- zobrazí detail vybavenia s galériou a hodnoteniami,
- zaregistruje sa a prihlási.

**Zákazník**
- vytvorí rezerváciu na zvolený termín (systém skontroluje dostupnosť),
- zobrazí zoznam svojich rezervácií a ich stav,
- zruší rezerváciu, kým nie je schválená,
- pridá hodnotenie k vybaveniu, ktoré si požičal,
- upraví svoj profil a zmení heslo.

**Správca**
- vytvorí, upraví a zmaže kategóriu,
- vytvorí, upraví a zmaže položku vybavenia, nahrá a zmaže jej fotografie,
- schváli alebo zamietne rezerváciu a označí vybavenie ako vrátené,
- vyfiltruje rezervácie podľa stavu a termínu,
- zmaže nevhodné hodnotenie,
- zmení rolu používateľa alebo ho zablokuje.

## Plánované entity
- **User** – používateľ systému. Atribúty: `id`, `meno`, `email` (unikátny), `heslo_hash`, `rola` (`zakaznik` / `admin`), `vytvoreny`.
- **Kategoria** – kategória vybavenia (napr. Bicykle, Zimné športy). Atribúty: `id`, `nazov`, `popis`.
- **Polozka** – konkrétny druh vybavenia na požičanie. Atribúty: `id`, `kategoria_id`, `nazov`, `popis`, `cena_za_den`, `pocet_kusov`, `aktivna`.
- **FotoPolozky** – fotografie uložené k položke. Atribúty: `id`, `polozka_id`, `cesta_k_suboru`, `povodny_nazov`, `poradie`.
- **Rezervacia** – rezervácia položky zákazníkom na termín. Atribúty: `id`, `user_id`, `polozka_id`, `datum_od`, `datum_do`, `pocet_kusov`, `celkova_cena`, `stav` (`caka` / `schvalena` / `zamietnuta` / `vratena` / `zrusena`), `poznamka`.
- **Hodnotenie** – hodnotenie položky zákazníkom. Atribúty: `id`, `user_id`, `polozka_id`, `pocet_hviezdiciek` (1–5), `komentar`, `vytvorene`.

## Vzťahy medzi entitami
- **Kategoria 1:N Polozka** – jedna kategória obsahuje viac položiek, položka patrí presne do jednej kategórie.
- **Polozka 1:N FotoPolozky** – položka má viac fotografií, fotografia patrí jednej položke.
- **User 1:N Rezervacia** – používateľ môže mať viac rezervácií.
- **Polozka 1:N Rezervacia** – položka môže byť rezervovaná opakovane v rôznych termínoch.
- **User 1:N Hodnotenie** a **Polozka 1:N Hodnotenie** – používateľ pridá viac hodnotení a položka ich môže mať viac. Dvojica (`user_id`, `polozka_id`) je unikátna, takže jeden používateľ hodnotí položku najviac raz.
- Medzi entitami `User` a `Polozka` je vzťah M:N realizovaný entitou `Rezervacia`. Jeden používateľ môže mať rezervovaných viac položiek a jedna položka môže byť rezervovaná viacerými používateľmi v rôznych termínoch.

## Hlavné stránky aplikácie
1. **Domovská stránka / katalóg** – zoznam vybavenia s filtrovaním podľa kategórie, ceny a termínu a so stránkovaním (AJAX).
2. **Detail vybavenia** – popis, galéria fotografií, cena, hodnotenia a formulár na rezerváciu.
3. **Registrácia a prihlásenie** – formuláre s validáciou na klientovi aj na serveri, prihlásenie cez AJAX.
4. **Moje rezervácie** – prehľad rezervácií zákazníka so stavmi a možnosťou zrušenia.
5. **Profil používateľa** – úprava osobných údajov a zmena hesla.
6. **Admin – správa vybavenia** – tabuľka položiek, pridanie, úprava a mazanie, upload a správa fotografií.
7. **Admin – správa rezervácií** – tabuľka s filtrom podľa stavu a termínu, schvaľovanie, zamietanie a označenie vrátenia (in-place zmena stavu cez AJAX).
8. **Admin – kategórie a používatelia** – správa kategórií a rolí používateľov.

## Rozdelenie funkcionality
**Základné funkcie**
- registrácia, prihlásenie a odhlásenie s hashovaním hesiel, role zákazník a admin s autorizáciou,
- CRUD nad kategóriami a vybavením, CRUD nad rezerváciami,
- katalóg s filtrovaním a stránkovaním cez AJAX,
- kontrola dostupnosti termínu pri rezervácii (bez prekrývania a nad rámec počtu kusov),
- upload a mazanie fotografií k položke s kontrolou typu a veľkosti súboru,
- zmena stavu rezervácie správcom cez AJAX (in-place),
- hodnotenie vybavenia zákazníkmi,
- validácia vstupov na strane klienta aj servera, ochrana proti SQL injection (prepared statements) a XSS,
- responzívny dizajn, aplikácia spustiteľná v Dockeri s importom ukážkových dát.

**Rozširujúce funkcie**
- kalendárový pohľad obsadenosti položky,
- externé API (napr. počasie pre termín rezervácie alebo kurzy mien),
- HTML5 API (napr. drag & drop upload fotografií),
- pokročilá validácia formulárov na klientovi,
- štatistiky pre správcu (najviac požičiavané vybavenie, tržby) s grafom,
- zľavy pre dlhšie rezervácie, e-mailové notifikácie o zmene stavu,
- použitie SASS/LESS a vlastný responzívny dizajn.
