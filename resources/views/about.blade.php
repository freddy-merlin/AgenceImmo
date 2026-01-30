@extends('layouts.app')

@section('title', 'ArtDecoNavigator - Votre Agence Immobilière')

@section('content')  


    <div class="container-fluid bg-white p-0">
   
        <!-- Header Start -->
        <div class="container-fluid header bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4">À propos de nous</h1> 
                        <nav aria-label="breadcrumb animated fadeIn">
                        <ol class="breadcrumb text-uppercase">
                            <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="#">Pages</a></li>
                            <li class="breadcrumb-item text-body active" aria-current="page">À propos</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 animated fadeIn">
                    <img class="img-fluid" src="img/header.jpg" alt="À propos d'ArtDecoNavigator">
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- About Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                        <div class="about-img position-relative overflow-hidden p-5 pe-0">
                            <img class="img-fluid w-100" src="img/about.jpg" alt="Équipe ArtDecoNavigator">
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                        <h1 class="mb-4">Notre histoire & expertise</h1>
                        <p class="mb-4">Fondée en 2008, ArtDecoNavigator s'est imposée comme un acteur majeur du marché immobilier français. Avec plus de 15 ans d'expérience, notre agence a accompagné plus de 2 500 familles dans la concrétisation de leurs projets immobiliers.</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Expertise locale et connaissance fine du marché</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Accompagnement personnalisé de A à Z</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Réseau national de partenaires certifiés</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Transparence et éthique dans toutes nos transactions</p>
                        <p><i class="fa fa-check text-primary me-3"></i>Service après-vente et suivi continu</p>
                        <a class="btn btn-primary py-3 px-5 mt-3" href="contact.html">Prendre rendez-vous</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Statistics Start -->
        <div class="container-fluid bg-primary py-5">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="bg-white text-center rounded p-4">
                            <i class="fa fa-home fa-3x text-primary mb-3"></i>
                            <h1 class="display-5 mb-0" data-toggle="counter-up">2,543</h1>
                            <span class="text-uppercase">Transactions réalisées</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="bg-white text-center rounded p-4">
                            <i class="fa fa-users fa-3x text-primary mb-3"></i>
                            <h1 class="display-5 mb-0" data-toggle="counter-up">15</h1>
                            <span class="text-uppercase">Années d'expérience</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="bg-white text-center rounded p-4">
                            <i class="fa fa-award fa-3x text-primary mb-3"></i>
                            <h1 class="display-5 mb-0" data-toggle="counter-up">12</h1>
                            <span class="text-uppercase">Prix d'excellence</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="bg-white text-center rounded p-4">
                            <i class="fa fa-smile fa-3x text-primary mb-3"></i>
                            <h1 class="display-5 mb-0" data-toggle="counter-up">98%</h1>
                            <span class="text-uppercase">Clients satisfaits</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistics End -->


        <!-- Values Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Nos valeurs</h1>
                    <p>Les principes fondamentaux qui guident chaque décision et chaque action au sein d'ArtDecoNavigator.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="service-item bg-light rounded p-4 text-center">
                            <i class="fa fa-handshake fa-3x text-primary mb-4"></i>
                            <h4 class="mb-3">Confiance</h4>
                            <p>Nous bâtissons des relations durables basées sur la transparence et l'honnêteté.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="service-item bg-light rounded p-4 text-center">
                            <i class="fa fa-star fa-3x text-primary mb-4"></i>
                            <h4 class="mb-3">Excellence</h4>
                            <p>Nous visons l'excellence dans chaque service pour dépasser vos attentes.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="service-item bg-light rounded p-4 text-center">
                            <i class="fa fa-heart fa-3x text-primary mb-4"></i>
                            <h4 class="mb-3">Engagement</h4>
                            <p>Votre satisfaction est notre priorité absolue, de la première rencontre au service après-vente.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Values End -->


        <!-- Team Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Notre équipe dirigeante</h1>
                    <p>Rencontrez les experts qui pilotent ArtDecoNavigator et mettent leur savoir-faire à votre service.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-1.jpg" alt="Sophie Martin">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Sophie Martin</h5>
                                <small>Directrice générale & Fondatrice</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-2.jpg" alt="Thomas Dubois">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Thomas Dubois</h5>
                                <small>Directeur commercial</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-3.jpg" alt="Émilie Laurent">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Émilie Laurent</h5>
                                <small>Responsable biens de prestige</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-4.jpg" alt="Julien Moreau">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Julien Moreau</h5>
                                <small>Responsable juridique & financier</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Team End -->


        <!-- Call to Action Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="bg-light rounded p-3">
                    <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                        <div class="row g-5 align-items-center">
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                                <img class="img-fluid rounded w-100" src="img/call-to-action.jpg" alt="Agent ArtDecoNavigator">
                            </div>
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="mb-4">
                                    <h1 class="mb-3">Rencontrez un de nos experts</h1>
                                    <p>Notre équipe est à votre disposition pour étudier votre projet et vous proposer des solutions adaptées à vos besoins.</p>
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