# Luxor — optimizacija performansi nakon Lighthouse provere

30.10.2026. Sve izmene su lokalne, bez commita, push-a ili cPanel deploymenta.

## Izmereno pre i posle

Isti lokalni Chrome, prazan HTTP keš, početna stranica, visina 950 px, DPR 1, očitavanje posle 2,2 sekunde. Ovo je merenje Resource Timing prenosa, **nije nova Lighthouse ocena** i ne uključuje HTML dokument u zbir resursa.

| Širina | Pre | Posle | Smanjenje |
| --- | ---: | ---: | ---: |
| 390 px | 4.188.967 B | 899.483 B | 78,5% |
| 1440 px | 6.768.842 B | 920.267 B | 86,4% |

Neprigušeni lokalni LCP u tom završnom uzorku: 408 ms na 390 px i 260 ms na 1440 px. Ti brojevi nisu uporedivi sa Lighthouse LCP-om 2,5 s iz priloženog izveštaja, jer uslovi CPU/mrežnog usporavanja nisu isti. Nova Lighthouse ocena mora se ponovo izmeriti u istom režimu kao prethodna.

## Promene

- Napravljeno 65 responsive WebP kopija za 11 stvarnih postojećih slika/logotipa/planova. Ukupno sve varijante zauzimaju oko 2,37 MB; izvorne slike oko 10,91 MB. Pregledač preuzima odgovarajuću varijantu, ne sve varijante.
- Originali u medijateci, ACF izbori, metapodaci i originalni linkovi za galeriju/uvećanje ostali su neizmenjeni.
- Hero koristi `<picture>` sa srcset/sizes umesto velikog CSS PNG-a. Sačuvani su cover kadriranje, visina slajda, gradijent i slider. Prvi slajd ima eager/high prioritet i odgovarajući responsive preload; drugi lazy/low.
- Hero tekst više ne čeka na AOS reveal animaciju. Ostale AOS animacije, slideri i galerije zadržani su.
- Logotipi imaju male responsive izvore i sačuvane proporcije. About/CTA pozadine takođe koriste optimizovane kopije.
- Dodato `font-display: swap` i rano učitavanje postojećih Regular/Bold WOFF2 fontova.
- Fancybox se ne učitava na fiksnim stranicama koje ga ne koriste; ostaje na stanovima/projektima i običnim stranicama sa fleksibilnim sadržajem. GSAP/ScrollTrigger učitavaju se kada urednički sadržaj koristi postojeće `bar-fill` animacije. Nisu uklanjane potrebne Bootstrap/AOS biblioteke ili skripte forme.
- Dodata statička cache pravila u temi: verzionisani CSS/JS godinu dana, fontovi mesec dana, ostale postojeće slike sedam dana; sadržajno heširane WebP kopije godinu dana. Stvarni HEAD odgovori potvrđuju `Cache-Control: max-age=31536000` za WebP/CSS/JS.
- Dodato lokalno WebP MIME pravilo: server sada ispravno vraća `Content-Type: image/webp`.
- Nema cache pravila za HTML, PHP, AJAX, administraciju, nonces ili kontakt formu.
- Dodati pristupačni nazivi mobilnom meniju, ikonama društvenih mreža i scroll linku, kao i nedostajući naziv offcanvas dijaloga. Footer logo sada ima odgovarajući naziv brenda.

## Fajlovi ovog koraka

U temi `wp-content/themes/vladastheme/`:

Novi:

- `inc/media.php` — manifest/responsive helperi, originalni fallback.
- `.htaccess` — cache samo za statičke tipove resursa.
- `src/images/optimized-media/manifest.json`, 65 `.webp` fajlova i `.htaccess`.
- `tools/export-responsive-media.php`, `tools/build-responsive-media.py` — ponovljiv lokalni postupak za buduće slike.
- `PERFORMANSE.md` — ovaj izveštaj.

Izmenjeni:

- `functions.php` — uključivanje media helpera.
- `inc/assets.php`, `inc/seo.php` — uslovno učitavanje, font/hero preload.
- `inc/_partials/_acf-page-header.php`, `inc/_partials/_navigation.php`, `footer.php`.
- `template-parts/sections/about-us.php`, `template-parts/sections/call.php`.
- `src/styles/scss/_base/_fonts.scss`.
- `src/styles/scss/_components/_headers/_header-home.scss`, `src/styles/scss/_components/_nav/_nav.scss`.
- `style.css` — produkcioni build.
- `tests/seo.php` — dopunjene provere originalnih i optimizovanih slika.

Pre ovog koraka napravljena je kopija teme `/tmp/luxor-performance-before-rrwe0ump/theme/`. Sve prethodne SEO izmene i korisnikova SCSS izmena `background-repeat: no-repeat` su sačuvane. `.cpanel.yml`, Docker, core, paketi i pluginovi nisu menjani ili ažurirani.

## Buduće slike

Promena ACF fotografije i dalje normalno radi. Za nove fotografije koje još nemaju optimizovanu kopiju koristi se originalni WordPress prikaz. Ako izvorni fajl nedostaje/promeni veličinu ili optimizovane datoteke nisu raspoložive, helper se bezbedno vraća na native prikaz. Manifest koristi relativnu putanju medijske datoteke, ne localhost domen i ne hardkodovani ID.

Ponovna izrada na razvojnom računaru (postojeći Python i ImageMagick, bez novih Python biblioteka), iz korena `luxor-investments`:

```sh
docker compose exec -T wordpress php /var/www/html/wp-content/themes/vladastheme/tools/export-responsive-media.php > /tmp/luxor-media.jsonl
python3 wordpress/wp-content/themes/vladastheme/tools/build-responsive-media.py --inventory /tmp/luxor-media.jsonl
```

Originali se ne prepisuju. Izmenjene/dodate optimizovane fajlove i manifest treba uključiti u budući Git commit. Ako se menjaju parametri enkodera/kompresije, treba promeniti i strategiju/verziju heša naziva da već keširani fajl ne zadrži isti URL.

## Provere

- Produkcioni Gulp build: uspešan, bez novih zavisnosti.
- PHP sintaksa 56 fajlova: prolazi.
- 36 PHP integracionih provera: prolazi, uključujući WebP izvore, ALT, originalni fallback i neizmenjene metapodatke.
- 37 regresionih browser provera sedam stranica na 320/390/768/1440 px: prolazi, nema prelivanja ni neuhvaćenih JS grešaka.
- 11 funkcionalnih provera galerija, lightbox-a, tabova, mobilnog menija, postojeće forme i no-JS prikaza: prolazi. Email nije slat.
- 13 dodatnih vizuelnih/proporcijskih provera: logo ne menja odnos stranica, hero tekst je odmah vidljiv, koriste se responsive WebP i imenovane kontrole.
- 178 HTTP/SEO/resurs provera: prolazi; manji broj zahteva nego ranije jer su uklonjene nepotrebne biblioteke.
- Vizuelno pregledan završni desktop screenshot početne.
- Završni pregled logova: bez PHP grešaka i bez neočekivanih 404/500 odgovora.

## Kako ponoviti Lighthouse

Na `http://localhost:8080/` osvežiti bez keša, zatvoriti druge aktivne testove i ponoviti isti Mobile/Desktop režim i ostale opcije kao ranije. Uporediti više pokretanja, ne samo jedno. Posebno pogledati „Improve image delivery“, cache i LCP.

Lighthouse CLI nije instaliran i nije instaliran dodatni paket radi ocene. Prijavljen je stvarno izmeren prenos; nije izmišljena nova ocena. Na cPanel hostingu treba proveriti da Apache/LiteSpeed primenjuje `.htaccess`; ako hosting koristi Nginx bez tih pravila, cache se podešava na serveru. Fajlovi su pripremljeni za postojeći deployment koji kopira temu, ali ništa nije objavljeno.

Preostali plugin resursi izvan teme mogu i dalje imati kratko keširanje. Nije menjana globalna serverska konfiguracija, prisilno pomeran jQuery ili uklanjan Bootstrap samo radi boljeg skora, kako bi postojeće forme i interakcije ostale ispravne.

## Reference

Optimizacija prati [Google smernice za LCP](https://web.dev/articles/optimize-lcp), posebno rano otkrivanje slike i smanjenje kašnjenja prikaza, i [Chrome smernice za cache lifetime](https://developer.chrome.com/docs/performance/insights/cache).
