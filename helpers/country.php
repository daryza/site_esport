<?php

# Liste propre et réutilisable
function getCountryList()
{
    return [
        # Europe
        'France',
        'Espagne',
        'Italie',
        'Allemagne',
        'Angleterre',
        'Portugal',
        'Belgique',
        'Pays-Bas',
        'Suisse',
        'Autriche',
        'Suède',
        'Danemark',
        'Norvège',
        'Finlande',
        'Pologne',
        'République Tchèque',
        'Croatie',
        'Serbie',
        'Turquie',

        # Maghreb
        'Maroc',
        'Algérie',
        'Tunisie',
        'Libye',
        'Mauritanie',

        # Afrique
        'Égypte',
        'Cameroun',

        # Amérique
        'États-Unis',
        'Mexique',
        'Brésil',
        'Argentine',

        # Autre
        'Autre'
    ];
}

# Map pays -> drapeau ISO
function countryFlag($pays)
{
    $map = [
        'France' => 'fr',
        'Espagne' => 'es',
        'Italie' => 'it',
        'Allemagne' => 'de',
        'Angleterre' => 'gb',
        'Portugal' => 'pt',
        'Belgique' => 'be',
        'Pays-Bas' => 'nl',
        'Suisse' => 'ch',
        'Autriche' => 'at',
        'Suède' => 'se',
        'Danemark' => 'dk',
        'Norvège' => 'no',
        'Finlande' => 'fi',
        'Pologne' => 'pl',
        'République Tchèque' => 'cz',
        'Croatie' => 'hr',
        'Serbie' => 'rs',
        'Turquie' => 'tr',

        # Maghreb
        'Maroc' => 'ma',
        'Algérie' => 'dz',
        'Tunisie' => 'tn',
        'Libye' => 'ly',
        'Mauritanie' => 'mr',

        # Afrique
        'Égypte' => 'eg',
        'Cameroun' => 'cm',

        # Amérique
        'États-Unis' => 'us',
        'Mexique' => 'mx',
        'Brésil' => 'br',
        'Argentine' => 'ar',

        # Autre
        'Autre' => 'un'
    ];

    return "https://flagcdn.com/w40/" . ($map[$pays] ?? 'un') . ".png";
}
