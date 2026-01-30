@extends('layouts.app')

@section('title', 'ArtDecoNavigator - Votre Agence Immobilière')

@section('content')

    <div class="container-fluid bg-white p-0">
       


        <!-- Header Start -->
        <div class="container-fluid header bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4">Types de biens</h1> 
                        <nav aria-label="breadcrumb animated fadeIn">
                        <ol class="breadcrumb text-uppercase">
                            <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="#">Biens</a></li>
                            <li class="breadcrumb-item text-body active" aria-current="page">Types de biens</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 animated fadeIn">
                    <img class="img-fluid" src="img/header.jpg" alt="Types de biens immobiliers">
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Category Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Nos types de biens</h1>
                    <p>Explorez notre catalogue complet de biens immobiliers. Que vous cherchiez un appartement en centre-ville, une maison de campagne ou un local commercial, nous avons ce qu'il vous faut.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="property-list.html">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-apartment.png" alt="Icon">
                                </div>
                                <h6>Appartements</h6>
                                <span>86 Biens disponibles</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="property-list.html">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-villa.png" alt="Icon">
                                </div>
                                <h6>Villas</h6>
                                <span>45 Biens disponibles</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="property-list.html">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-house.png" alt="Icon">
                                </div>
                                <h6>Maisons</h6>
                                <span>120 Biens disponibles</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="property-list.html">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-housing.png" alt="Icon">
                                </div>
                                <h6>Bureaux</h6>
                                <span>32 Biens disponibles</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="property-list.html">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-building.png" alt="Icon">
                                </div>
                                <h6>Immeubles</h6>
                                <span>15 Biens disponibles</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="property-list.html">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-neighborhood.png" alt="Icon">
                                </div>
                                <h6>Terrains</h6>
                                <span>68 Biens disponibles</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="property-list.html">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-condominium.png" alt="Icon">
                                </div>
                                <h6>Locaux commerciaux</h6>
                                <span>42 Biens disponibles</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                        <a class="cat-item d-block bg-light text-center rounded p-3" href="property-list.html">
                            <div class="rounded p-4">
                                <div class="icon mb-3">
                                    <img class="img-fluid" src="img/icon-luxury.png" alt="Icon">
                                </div>
                                <h6>Biens de prestige</h6>
                                <span>24 Biens disponibles</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Category End -->

        <!-- Property Types Description Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 800px;">
                    <h1 class="mb-3">Détails par type de bien</h1>
                    <p>Chaque type de bien répond à des besoins spécifiques. Découvrez ci-dessous les caractéristiques principales de chaque catégorie.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="property-type-item bg-light rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon me-3" style="width: 60px; height: 60px;">
                                    <img class="img-fluid" src="img/icon-apartment.png" alt="Appartement">
                                </div>
                                <h4 class="mb-0">Appartements</h4>
                            </div>
                            <p>Idéal pour les célibataires, couples ou petites familles. Disponible en centre-ville ou en périphérie, du studio aux grandes surfaces.</p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-primary me-2"></i>Du studio au 5 pièces</li>
                                <li><i class="fa fa-check text-primary me-2"></i>Neuf ou ancien</li>
                                <li><i class="fa fa-check text-primary me-2"></i>Avec ou sans balcon/terrasse</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="property-type-item bg-light rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon me-3" style="width: 60px; height: 60px;">
                                    <img class="img-fluid" src="img/icon-house.png" alt="Maison">
                                </div>
                                <h4 class="mb-0">Maisons</h4>
                            </div>
                            <p>Parfait pour les familles qui recherchent de l'espace et un jardin. Disponible en ville, en banlieue ou à la campagne.</p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-primary me-2"></i>Maisons individuelles ou jumelées</li>
                                <li><i class="fa fa-check text-primary me-2"></i>Avec jardin, garage, piscine</li>
                                <li><i class="fa fa-check text-primary me-2"></i>De 2 à 6 chambres et plus</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="property-type-item bg-light rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon me-3" style="width: 60px; height: 60px;">
                                    <img class="img-fluid" src="img/icon-building.png" alt="Immeuble">
                                </div>
                                <h4 class="mb-0">Immeubles</h4>
                            </div>
                            <p>Investissement locatif ou professionnel. Immeubles de rapport, résidentiels ou mixtes pour les investisseurs.</p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-primary me-2"></i>Rendement locatif attractif</li>
                                <li><i class="fa fa-check text-primary me-2"></i>De 2 à 20 appartements</li>
                                <li><i class="fa fa-check text-primary me-2"></i>Possibilité de rénovation</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="property-type-item bg-light rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon me-3" style="width: 60px; height: 60px;">
                                    <img class="img-fluid" src="img/icon-neighborhood.png" alt="Terrain">
                                </div>
                                <h4 class="mb-0">Terrains</h4>
                            </div>
                            <p>Pour construire votre maison ou investir dans un projet immobilier. Terrains viabilisés, constructibles ou agricoles.</p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-primary me-2"></i>Terrains constructibles</li>
                                <li><i class="fa fa-check text-primary me-2"></i>Viabilisés ou à viabiliser</li>
                                <li><i class="fa fa-check text-primary me-2"></i>De 500 m² à plusieurs hectares</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="property-type-item bg-light rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon me-3" style="width: 60px; height: 60px;">
                                    <img class="img-fluid" src="img/icon-condominium.png" alt="Local commercial">
                                </div>
                                <h4 class="mb-0">Locaux commerciaux</h4>
                            </div>
                            <p>Pour lancer ou développer votre activité. Boutiques, restaurants, bureaux en centre-ville ou zones commerciales.</p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-primary me-2"></i>Boutiques, restaurants, bureaux</li>
                                <li><i class="fa fa-check text-primary me-2"></i>En centre-ville ou zones commerciales</li>
                                <li><i class="fa fa-check text-primary me-2"></i>À louer ou à vendre</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="property-type-item bg-light rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon me-3" style="width: 60px; height: 60px;">
                                    <img class="img-fluid" src="img/icon-luxury.png" alt="Bien de prestige">
                                </div>
                                <h4 class="mb-0">Biens de prestige</h4>
                            </div>
                            <p>Pour une clientèle exigeante recherchant l'exceptionnel. Propriétés d'exception avec des prestations haut de gamme.</p>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-primary me-2"></i>Villas luxueuses, châteaux</li>
                                <li><i class="fa fa-check text-primary me-2"></i>Piscine, tennis, parc privé</li>
                                <li><i class="fa fa-check text-primary me-2"></i>Services sur mesure</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Property Types Description End -->

        <!-- Call to Action Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="bg-light rounded p-3">
                    <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                        <div class="row g-5 align-items-center">
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                                <img class="img-fluid rounded w-100" src="img/call-to-action.jpg" alt="Agent immobilier">
                            </div>
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="mb-4">
                                    <h1 class="mb-3">Besoin d'aide pour choisir ?</h1>
                                    <p>Notre équipe d'experts est à votre disposition pour vous guider dans le choix du bien qui correspond le mieux à vos besoins et à votre budget.</p>
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
        

        


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
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