@extends('layouts.app')

@section('title', 'ArtDecoNavigator - Votre Agence Immobilière')

@section('content')
    <!-- Header Start -->
    <div class="container-fluid header bg-white p-0">
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn mb-4">Trouvez <span class="text-primary">Votre Chez-Vous</span> Idéal</h1>
                <p class="animated fadeIn mb-4 pb-2">ArtDecoNavigator vous accompagne dans tous vos projets immobiliers. Que vous cherchiez à acheter, vendre ou louer, notre équipe d'experts est à votre service.</p>
                <a href="{{ route('property.list') }}" class="btn btn-primary py-3 px-5 me-3 animated fadeIn">Commencer</a>
            </div>
            <div class="col-md-6 animated fadeIn">
                <div class="owl-carousel header-carousel">
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="{{ asset('img/carousel-1.jpg') }}" alt="Maison moderne">
                    </div>
                    <div class="owl-carousel-item">
                        <img class="img-fluid" src="{{ asset('img/carousel-2.jpg') }}" alt="Appartement lumineux">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->

    <!-- Search Start -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <div class="row g-2">
                <div class="col-md-10">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control border-0 py-3" placeholder="Mot-clé, ville, quartier...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select border-0 py-3">
                                <option selected>Type de bien</option>
                                <option value="1">Maison</option>
                                <option value="2">Appartement</option>
                                <option value="3">Terrain</option>
                                <option value="4">Bureau</option>
                                <option value="5">Local commercial</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select border-0 py-3">
                                <option selected>Transaction</option>
                                <option value="1">À vendre</option>
                                <option value="2">À louer</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark border-0 w-100 py-3">
                        <a href="{{ route('property.list') }}" class="text-white text-decoration-none">
                            Rechercher
                        </a>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Search End -->

    <!-- Category Start -->
    <div class="container-fluid py-5" style="background-color: white">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Types de Biens</h1>
                    <p>Découvrez notre large sélection de biens immobiliers adaptés à tous vos besoins et à toutes vos envies.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-apartment.png" alt="Icon">
                                </div>
                                <h6>Appartements</h6>
                                <span>86 Biens</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-villa.png" alt="Icon">
                                </div>
                                <h6>Villas</h6>
                                <span>45 Biens</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-house.png" alt="Icon">
                                </div>
                                <h6>Maisons</h6>
                                <span>120 Biens</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-housing.png" alt="Icon">
                                </div>
                                <h6>Bureaux</h6>
                                <span>32 Biens</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-building.png" alt="Icon">
                                </div>
                                <h6>Immeubles</h6>
                                <span>15 Biens</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-neighborhood.png" alt="Icon">
                                </div>
                                <h6>Terrains</h6>
                                <span>68 Biens</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-condominium.png" alt="Icon">
                                </div>
                                <h6>Locaux commerciaux</h6>
                                <span>42 Biens</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-luxury.png" alt="Icon">
                                </div>
                                <h6>Biens de prestige</h6>
                                <span>24 Biens</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <!-- Category End -->

         <!-- About Start -->
        <div class="container-fluid py-5" style="background-color: white">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                        <div class="about-img position-relative overflow-hidden p-5 pe-0">
                            <img class="img-fluid w-100" src="img/about.jpg" alt="Équipe ArtDecoNavigator">
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                        <h1 class="mb-4">Votre Partenaire Immobilier de Confiance</h1>
                        <p class="mb-4">Fort de 15 ans d'expérience, ArtDecoNavigator s'est imposé comme un acteur majeur du marché immobilier. Notre mission : vous accompagner dans la concrétisation de vos projets avec professionnalisme et transparence.</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Expertise locale et marché</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Accompagnement personnalisé</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Réseau de partenaires qualifiés</p>
                        <a class="btn btn-primary py-3 px-5 mt-3" href="about.html">En savoir plus</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


                <!-- Interior Design Preview Start -->
        <div class="container-fluid bg-light py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s" style="color: white;">
                        <h1 class="mb-4">Transformez Votre Intérieur</h1>
                        <p class="mb-4">Notre équipe de décorateurs d'intérieur vous accompagne pour créer des espaces uniques qui vous ressemblent. Du concept à la réalisation, nous gérons tout pour vous.</p>
                        <p><i class="fas fa-palette text-primary me-3"></i>Conseils en décoration et aménagement</p>
                        <p><i class="fas fa-couch text-primary me-3"></i>Sélection de mobilier et d'accessoires</p>
                        <p><i class="fas fa-paint-brush text-primary me-3"></i>Rénovation et relooking d'intérieur</p>
                        <p><i class="fas fa-ruler-combined text-primary me-3"></i>Optimisation de l'espace et plans 3D</p>
                        <a href="interior-design.html" class="btn btn-primary py-3 px-5 mt-3">Découvrir nos réalisations</a>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                        <div class="row g-3">
                            <div class="col-6">
                                <img class="img-fluid rounded" src="img/deco1.jpg" alt="Salon moderne">
                            </div>
                            <div class="col-6">
                                <img class="img-fluid rounded" src="img/deco2.jpg" alt="Cuisine design">
                            </div>
                            <div class="col-6">
                                <img class="img-fluid rounded" src="img/deco3.jpg" alt="Chambre élégante">
                            </div>
                            <div class="col-6">
                                <img class="img-fluid rounded" src="img/deco4.jpg" alt="Bureau aménagé">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Interior Design Preview End -->
        <!-- Property List Start -->
        <div class="container-fluid py-5" style="background-color: white">
            <div class="container">
                <div class="row g-0 gx-5 align-items-end">
                    <div class="col-lg-6">
                        <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                            <h1 class="mb-3">Nos Dernières Offres</h1>
                            <p>Découvrez une sélection de nos meilleurs biens actuellement disponibles à la vente ou à la location.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                        <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                            <li class="nav-item me-2">
                                <a class="btn btn-outline-primary active" data-bs-toggle="pill" href="#tab-1">À la une</a>
                            </li>
                            <li class="nav-item me-2">
                                <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-2">À vendre</a>
                            </li>
                            <li class="nav-item me-0">
                                <a class="btn btn-outline-primary" data-bs-toggle="pill" href="#tab-3">À louer</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="position-relative overflow-hidden">
                                        <a href=""><img class="img-fluid" src="img/property-1.jpg" alt="Appartement centre-ville"></a>
                                        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">À vendre</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Appartement</div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">250 000 FCFA</h5>
                                        <a class="d-block h5 mb-2" href="">Appartement 3 pièces centre-ville</a>
                                        <p><i class="fa fa-map-marker-alt text-primary me-2"></i>Cotonou</p>
                                    </div>
                                    <div class="d-flex border-top">
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-primary me-2"></i>75 m²</small>
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-primary me-2"></i>3 Chambres</small>
                                        <small class="flex-fill text-center py-2"><i class="fa fa-bath text-primary me-2"></i>2 Salles de bain</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="position-relative overflow-hidden">
                                        <a href=""><img class="img-fluid" src="img/property-2.jpg" alt="Villa moderne"></a>
                                        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">À louer</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Villa</div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">2 500 Fcfa/mois</h5>
                                        <a class="d-block h5 mb-2" href="">Villa contemporaine avec piscine</a>
                                        <p><i class="fa fa-map-marker-alt text-primary me-2"></i>Calavi</p>
                                    </div>
                                    <div class="d-flex border-top">
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-primary me-2"></i>180 m²</small>
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-primary me-2"></i>4 Chambres</small>
                                        <small class="flex-fill text-center py-2"><i class="fa fa-bath text-primary me-2"></i>3 Salles de bain</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="position-relative overflow-hidden">
                                        <a href=""><img class="img-fluid" src="img/property-3.jpg" alt="Bureau moderne"></a>
                                        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">À vendre</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Bureau</div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">420 000 Fcfa</h5>
                                        <a class="d-block h5 mb-2" href="">Espace bureau La Défense</a>
                                        <p><i class="fa fa-map-marker-alt text-primary me-2"></i> Cotonou </p>
                                    </div>
                                    <div class="d-flex border-top">
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-primary me-2"></i>110 m²</small>
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-primary me-2"></i>Open Space</small>
                                        <small class="flex-fill text-center py-2"><i class="fa fa-bath text-primary me-2"></i>2 Sanitaires</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="position-relative overflow-hidden">
                                        <a href=""><img class="img-fluid" src="img/property-4.jpg" alt="Terrain constructible"></a>
                                        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">À vendre</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Terrain</div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">85 000 Fcfa</h5>
                                        <a class="d-block h5 mb-2" href="">Terrain constructible 800m²</a>
                                        <p><i class="fa fa-map-marker-alt text-primary me-2"></i>Calavi</p>
                                    </div>
                                    <div class="d-flex border-top">
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-primary me-2"></i>800 m²</small>
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-primary me-2"></i>Constructible</small>
                                        <small class="flex-fill text-center py-2"><i class="fa fa-bath text-primary me-2"></i>Viabilisé</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="position-relative overflow-hidden">
                                        <a href=""><img class="img-fluid" src="img/property-5.jpg" alt="Maison de campagne"></a>
                                        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">À vendre</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Maison</div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">375 000 Fcfa</h5>
                                        <a class="d-block h5 mb-2" href="">Maison de campagne rénovée</a>
                                        <p><i class="fa fa-map-marker-alt text-primary me-2"></i>Cotonou</p>
                                    </div>
                                    <div class="d-flex border-top">
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-primary me-2"></i>150 m²</small>
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-primary me-2"></i>4 Chambres</small>
                                        <small class="flex-fill text-center py-2"><i class="fa fa-bath text-primary me-2"></i>2 Salles de bain</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                                <div class="property-item rounded overflow-hidden">
                                    <div class="position-relative overflow-hidden">
                                        <a href=""><img class="img-fluid" src="img/property-6.jpg" alt="Local commercial"></a>
                                        <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">À louer</div>
                                        <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">Local commercial</div>
                                    </div>
                                    <div class="p-4 pb-0">
                                        <h5 class="text-primary mb-3">1 800 Fcfa/mois</h5>
                                        <a class="d-block h5 mb-2" href="">Local commercial centre-ville</a>
                                        <p><i class="fa fa-map-marker-alt text-primary me-2"></i>Cotonou</p>
                                    </div>
                                    <div class="d-flex border-top">
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-primary me-2"></i>90 m²</small>
                                        <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-primary me-2"></i>Bureau</small>
                                        <small class="flex-fill text-center py-2"><i class="fa fa-bath text-primary me-2"></i>Sanitaire</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                                <a class="btn btn-primary py-3 px-5" href="property-list.html">Voir plus de biens</a>
                            </div>
                        </div>
                    </div>
                    <!-- Les autres onglets (tab-2 et tab-3) gardent une structure similaire -->
                </div>
            </div>
        </div>
        <!-- Property List End -->


        <!-- Call to Action Start -->
        <div class="container-fluid py-5" style="background-color: white">
            <div class="container">
                <div class="bg-light rounded p-3">
                    <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                        <div class="row g-5 align-items-center">
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                                <img class="img-fluid rounded w-100" src="img/call-to-action.jpg" alt="Agent immobilier">
                            </div>
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="mb-4">
                                    <h1 class="mb-3">Contactez Notre Agent Certifié</h1>
                                    <p>Notre équipe d'experts est à votre disposition pour répondre à toutes vos questions et vous accompagner dans votre projet immobilier.</p>
                                </div>
                                <a href="tel:+33123456789" class="btn btn-primary py-3 px-4 me-2"><i class="fa fa-phone-alt me-2"></i>Nous appeler</a>
                                <a href="contact.html" class="btn btn-dark py-3 px-4"><i class="fa fa-calendar-alt me-2"></i>Prendre rendez-vous</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Call to Action End -->


        <!-- Team Start -->
        <div class="container-fluid py-5" style="background-color: white">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Nos Agents Immobiliers</h1>
                    <p>Rencontrez notre équipe d'experts passionnés qui sauront vous guider et vous conseiller pour concrétiser vos projets.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-1.jpg" alt="Agent Sophie Martin">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Sophie Martin</h5>
                                <small>Directrice d'agence</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-2.jpg" alt="Agent Thomas Dubois">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Thomas Dubois</h5>
                                <small>Conseiller immobilier senior</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-3.jpg" alt="Agent Émilie Laurent">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Émilie Laurent</h5>
                                <small>Spécialiste biens de prestige</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-4.jpg" alt="Agent Julien Moreau">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Julien Moreau</h5>
                                <small>Expert locations</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Team End -->


        <!-- Testimonial Start -->
        <div class="container-fluid py-5" style="background-color: white">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Nos Clients Témoignent</h1>
                    <p>Découvrez les retours d'expérience de nos clients satisfaits qui nous ont fait confiance pour leurs projets immobiliers.</p>
                </div>
                <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <p>Excellent service ! L'équipe d'ArtDecoNavigator nous a accompagnés avec professionnalisme pour l'achat de notre première maison. Un grand merci !</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="img/testimo nial-1.jpg" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">Marie  </h6>
                                    <small>Acheteuse</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <p>Vente de mon appartement réalisée en moins de 2 mois grâce à l'efficacité de Sophie. Je recommande vivement ArtDecoNavigator !</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="img/testimoni al-2.jpg" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">Pierre  </h6>
                                    <small>Vendeur</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <p>Un suivi personnalisé et des conseils avisés pour notre investissement locatif. Une agence sérieuse et transparente.</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="img/testimon ial-3.jpg" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">Camille  </h6>
                                    <small>Investisseuse</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->

@endsection

@push('scripts')
    <script>
        // Scripts spécifiques à la page d'accueil
        $(document).ready(function(){
            // Initialiser les carousels
            $('.header-carousel').owlCarousel({
                items: 1,
                loop: true,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 5000,
                smartSpeed: 1000
            });

            $('.testimonial-carousel').owlCarousel({
                items: 1,
                loop: true,
                nav: false,
                dots: true,
                autoplay: true,
                autoplayTimeout: 6000,
                smartSpeed: 1000,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    992: {
                        items: 3
                    }
                }
            });
        });
    </script>
@endpush