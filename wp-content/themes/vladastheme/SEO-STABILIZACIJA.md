# Luxor Investments — SEO i stabilizacija, 30.10.2026.

Sve izmene su lokalne. Nisu rađeni commit, push ili cPanel deployment. WordPress, pluginovi, zavisnosti, Docker, `.cpanel.yml`, postojeći meniji i poslovni sadržaj nisu menjani.

## Šta je popravljeno

- Tema ima `title-tag` podršku. Yoast je jedini izvor meta description/canonical/schema izlaza; uklonjeno je paralelno ručno ispisivanje opisa.
- Podrazumevani SEO naslov početne opisuje izgradnju/prodaju stanova. Naslov stana može uključiti stvarnu kvadraturu i projekat. Ručno upisani Yoast naslovi, opisi i canonical vrednosti imaju prednost.
- Rezervni opisi koriste postojeće ACF/WP tekstove, bez izmišljanja lokacije, pogodnosti, cena ili poslovnih tvrdnji.
- Postojeći ACF logo omogućava Yoast-ov Organization entitet kada poseban Yoast logo nije izabran. Koriste se postojeći telefon/email/profili; nije dodat paralelni schema blok niti ocene/recenzije.
- Javni jezik odgovara srpskoj latinici, bez promene admin jezika; budući WPML prevodi na drugim jezicima nisu prisilno prebačeni na srpski. WPML trenutno nije aktivan.
- Na proveravanim stranicama je po jedan H1. Sekcijski naslovi su H2 sa sačuvanim postojećim font metrikama. Naziv stana je vidljiv i na desktopu.
- Stan ima poseban `single-stanovi.php` i sadržaj `content-stanovi.php`. Kompletan postojeći prikaz stana je premešten, a ne obrisan. Obične objave više ne dobijaju stanarski raspored. Header/footer pozivi nisu ugnježdeni u sadržaju.
- Ispravljeni su prazni osnovni page/index/archive/search/404 šabloni, dokument shell i položaj `wp_footer()`; dodat je `wp_body_open()`.
- Projekat i stan su povezani vidljivim breadcrumb linkom i Yoast breadcrumb grafom.
- `/projekti/` i `/stanovi/` preusmeravaju 301 na postojeće stranice `/projekti-stranica/` i `/prodaja-stanova/`. URL-ovi pojedinačnih projekata i stanova nisu menjani.
- Stanovi su u početnom serverskom HTML-u. Isti renderer i query služe početni prikaz i AJAX, uz postojeći izgled kartica. Po stranici se prikazuje do 12 stanova i postoje obični linkovi paginacije; trenutno je objavljeno šest stanova.
- Query validira tipove podataka, dozvoljene statuse i broj filtera. Zadržani su postojeći nonce i AJAX action nazivi. Paginacija radi i bez JavaScript-a; GET forma omogućava i osnovno filtriranje bez JS-a.
- AJAX radi samo na stranici ponude stanova. Podržani su prekid prethodnog zahteva, ignorisanje zastarelog odgovora, timeout, greška veze i blokiran localStorage. Prethodni rezultati ostaju vidljivi ako zahtev ne uspe.
- URL filteri imaju prednost nad zapamćenim filterima. Filtrirane varijante su noindex/follow; Yoast na noindex varijantama može izostaviti canonical, što je očekivano. Nefiltrirana paginacija ima sopstveni canonical.
- Biblioteke se učitavaju jednom, sa zavisnostima i verzijama prema promeni fajla. Uklonjeni su pozivi nepostojećih Fancybox/TextPlugin fajlova i dupli GSAP. Debug i produkcija koriste isti skup potrebnih zavisnosti.
- JS za pojedinačni projekat/stan i filtere učitava se samo u odgovarajućem kontekstu. Očuvani su Bootstrap, AOS, galerije i postojeće animacije. Hero slika se preučitava gde postoji.
- Ispravljeni su pronađeni izlazi URL-ova/logotipa/kontakata u navigaciji, headeru i footeru. Tekst adrese više se ne koristi kao običan relativni URL. WhatsApp koristi sačuvani link ako postoji; inače normalizuje potvrđeni srpski lokalni broj u međunarodni oblik.
- Validno WordPress zaglavlje teme ostaje sačuvano i posle SCSS builda.

## Rezervna kopija i sačuvane izmene

Početni Git radni folder bio je čist. Kopija cele teme pre rada je u `/tmp/luxor-seo-before-kbi_vmul/theme/`. Tokom rada pojavila se zasebna izmena `background-repeat: no-repeat` u `_header-home.scss`; nije prepisana i uključena je u build.

Poređenje pre/posle potvrđuje da postojeće stranice, ID-jevi, pojedinačni URL-ovi, aktivni pluginovi i Yoast opcije nisu promenjeni. ACF polja i vrednosti nisu prepisivani. Normalan WordPress rad može osvežavati svoje keševe; nisu rađene migracije ili urednički upisi u podatke.

## Fajlovi

Putanje su relativne u odnosu na `wordpress/`.

Novi:

- `.babelrc.json` — koristi postojeći instalirani Babel preset, bez novih paketa.
- `wp-content/themes/vladastheme/inc/assets.php`
- `wp-content/themes/vladastheme/inc/seo.php`
- `wp-content/themes/vladastheme/inc/apartments.php`
- `wp-content/themes/vladastheme/single-stanovi.php`
- `wp-content/themes/vladastheme/template-parts/post/content-stanovi.php` — premešten postojeći prikaz stana.
- `wp-content/themes/vladastheme/template-parts/apartments/card.php` — izdvojena postojeća kartica.
- `wp-content/themes/vladastheme/tests/seo.php`
- `wp-content/themes/vladastheme/SEO-STABILIZACIJA.md` — ovaj izveštaj.

Izmenjeni u temi:

- `functions.php`, `header.php`, `footer.php`
- `page.php`, `index.php`, `archive.php`, `search.php`, `404.php`, `single.php`, `single-projekti.php`
- `inc/_partials/_navigation.php`, `inc/_partials/_acf-page-header.php`
- `template-parts/post/content.php`
- `template-parts/sections/about-us.php`, `why.php`, `offers.php`, `contact.php`, `call.php`, `archive-projekti.php`, `filters.php`
- `template-parts/template-kontakt.php`
- `src/scripts/src/script.js`, `src/scripts/src/filters.js`
- `src/styles/scss/style.scss`, `src/styles/scss/_components/_pages/_sections/_about-us.scss`
- `style.css`, `bundles/scripts/scripts.min.js` — produkcioni build.

Zasebna sačuvana izmena korisnika: `src/styles/scss/_components/_headers/_header-home.scss`.

## Izvršene provere

- Produkcioni Gulp build je uspešan sa postojećim Node 20 i instaliranim zavisnostima. Postojeća Sass upozorenja o zastarelim API-jima nisu rešavana ažuriranjem paketa.
- PHP sintaksa 54 fajla i sintaksa svih 11 izvornih JS fajlova prolaze.
- 30 PHP integracionih provera: upiti, validacija, projekti/statusi, ograničena paginacija i ekstremni ulaz, očuvanje ručnih Yoast unosa, jezik/WPML, Organization logo i WhatsApp. Testovi ne menjaju poslovne podatke.
- 192 HTTP/SEO provere: ključne stranice, resursi, jedan title/H1/opis, jedan Yoast graf/Organization, robots/canonical za filtere, validni sitemap-ovi i njihovi ciljevi, arhivska preusmeravanja, pretraga i stvarni HTTP 404.
- 37 osnovnih browser provera: sedam stranica na 320/390/768/1440 px, vidljiv naziv stana, početnih šest kartica, filtriranje/reset, greška mreže i blokirano skladište. Bez horizontalnog prelivanja ili neuhvaćenih JS grešaka.
- 11 funkcionalnih browser provera: slider i lightbox stana, galerija projekta, tabovi, mobilni meni, kontakt forma i prikaz/filteri bez JS-a. Kontakt poruke nisu slane.
- Pregledani lokalni logovi: bez PHP fatal/warning/notice grešaka; zabeleženi 404 je namerno testirana nepostojeća stranica.
- `git diff --check` prolazi. Nema promene `.cpanel.yml`, paketa, core-a ili Docker konfiguracije.

Osnovne komande iz korena Luxor projekta:

```sh
docker compose exec -T wordpress php /var/www/html/wp-content/themes/vladastheme/tests/seo.php
```

Build iz `wordpress/`, sa postojećim Node 20:

```sh
BABEL_DISABLE_CACHE=1 node node_modules/gulp/bin/gulp.js build
```

## Pre objavljivanja

Lokalno otvoriti `http://localhost:8080/` i proveriti početnu, prodaju stanova, projekat Telep/Adice, jedan stan, kontakt i o investitoru. Na telefonu proveriti meni, filtere, galerije i kontakt dugmad. Naziv stana je namerno sada vidljiv i na desktopu.

Posle odobrenja izmene treba commitovati zajedno sa NOVIM PHP fajlovima i izgrađenim CSS/JS resursima — samo `git commit -am` ne uključuje nove fajlove. Push/cPanel deployment nisu pokretani. Postojeća cPanel konfiguracija kopira samo temu; produkcioni build je zato već pripremljen u temi.

Zahtevaju produkcioni domen/pristup i nisu potvrđeni lokalnim testom:

- HTTPS i jedinstvena www/ne-www verzija na hostingu.
- Search Console verifikacija, slanje produkcionog sitemap-a i pregled stvarnog indeksiranja.
- Stvarni Core Web Vitals/PageSpeed rezultati na hostingu; nije obećana ocena ili pozicija na Google-u.
- Isporuka kontakt emaila/SMTP-a. Forma i njeni skriptovi su provereni, ali test poruka nije poslata.
- Ručna dopuna jedinstvenih opisa projekata/stanova i preciznih ALT opisa fotografija po potrebi. Postojeći sadržaj nije menjan ili izmišljan; sve je i dalje uređivo kroz postojeće ACF/WP/Yoast ekrane.
