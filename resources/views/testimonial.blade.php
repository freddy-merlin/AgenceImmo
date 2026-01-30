@extends('layouts.app')

@section('title', 'ArtDecoNavigator - Votre Agence Immobilière')

@section('content')

    <div class="container-fluid bg-white p-0">
        


        <!-- Header Start -->
        <div class="container-fluid header bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4">Témoignages clients</h1> 
                        <nav aria-label="breadcrumb animated fadeIn">
                        <ol class="breadcrumb text-uppercase">
                            <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="#">Pages</a></li>
                            <li class="breadcrumb-item text-body active" aria-current="page">Témoignages</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 animated fadeIn">
                    <img class="img-fluid" src="img/header.jpg" alt="Témoignages clients ArtDecoNavigator">
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Testimonial Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Ce que disent nos clients</h1>
                    <p>Découvrez les retours d'expérience de nos clients qui nous ont fait confiance pour concrétiser leurs projets immobiliers.</p>
                </div>
                <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rating text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p>"Un service exceptionnel ! L'équipe d'ArtDecoNavigator nous a accompagnés avec professionnalisme et réactivité pour l'achat de notre première maison. Sophie a été d'une grande patience et nous a guidés à chaque étape."</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="img/testimon ial-1.jpg" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">Marie  </h6>
                                    <small>Acheteuse - Premier achat</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rating text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p>"Vente de mon appartement réalisée en moins de 2 mois grâce à l'expertise de Thomas. Transparence, conseils avisés et négociation au top. Je recommande vivement ArtDecoNavigator à tous mes proches !"</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="img/testimo nial-2.jpg" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">Pierre  </h6>
                                    <small>Vendeur - Cotonou 15ème</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rating text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p>"Investissement locatif réussi grâce aux conseils d'Émilie. Elle a su trouver le bien parfait avec un excellent rendement. Le suivi après-vente est impeccable. Une agence sérieuse et compétente."</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="img/testi monial-3.jpg" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">Sophie  </h6>
                                    <small>Investisseuse - Bordeaux</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rating text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p>"Nous cherchions une maison pour notre famille depuis 6 mois. Julien nous a trouvé LA perle rare en 3 semaines ! Son réseau et sa connaissance du marché sont impressionnants. Merci pour tout !"</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="img/testimon ial-1.jpg" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">La famille Moreau</h6>
                                    <small>Famille avec enfants - Lyon</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item bg-light rounded p-3">
                        <div class="bg-white border rounded p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rating text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p>"Expatrié de retour en Bénin, j'avais besoin d'un accompagnement spécial. Caroline a été parfaite : disponible, compétente et très rassurante. Elle a géré toutes les démarches à distance. Bravo !"</p>
                            <div class="d-flex align-items-center">
                                <img class="img-fluid flex-shrink-0 rounded" src="img/testim onial-2.jpg" style="width: 45px; height: 45px;">
                                <div class="ps-3">
                                    <h6 class="fw-bold mb-1">Jean Dupont</h6>
                                    <small>Expatrié de retour - Singapour</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->

        <!-- Statistics Start -->
        <div class="container-fluid bg-primary py-5">
            <div class="container">
                <div class="text-center text-white mb-5 wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="mb-3">Notre satisfaction client en chiffres</h1>
                    <p>Des résultats qui parlent d'eux-mêmes</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="bg-white text-center rounded p-4">
                            <i class="fa fa-smile fa-3x text-primary mb-3"></i>
                            <h1 class="display-5 mb-0" data-toggle="counter-up">98</h1>
                            <span class="text-uppercase">% de clients satisfaits</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="bg-white text-center rounded p-4">
                            <i class="fa fa-home fa-3x text-primary mb-3"></i>
                            <h1 class="display-5 mb-0" data-toggle="counter-up">2,543</h1>
                            <span class="text-uppercase">Projets réalisés</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="bg-white text-center rounded p-4">
                            <i class="fa fa-clock fa-3x text-primary mb-3"></i>
                            <h1 class="display-5 mb-0" data-toggle="counter-up">45</h1>
                            <span class="text-uppercase">Jours de vente moyens</span>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="bg-white text-center rounded p-4">
                            <i class="fa fa-award fa-3x text-primary mb-3"></i>
                            <h1 class="display-5 mb-0" data-toggle="counter-up">15</h1>
                            <span class="text-uppercase">Prix d'excellence</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistics End -->
 

        <!-- Call to Action Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="bg-light rounded p-3">
                    <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
                        <div class="row g-5 align-items-center">
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                                <img class="img-fluid rounded w-100" src="img/call-to-action.jpg" alt="Rencontre avec un agent">
                            </div>
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="mb-4">
                                    <h1 class="mb-3">Partagez votre expérience avec nous</h1>
                                    <p>Votre satisfaction est notre plus belle réussite. Si vous avez apprécié nos services, n'hésitez pas à nous laisser votre avis ou à partager votre expérience avec vos proches.</p>
                                </div>
                                <a href="contact.html" class="btn btn-primary py-3 px-4 me-2"><i class="fa fa-comment me-2"></i>Laisser un témoignage</a>
                                <a href="property-list.html" class="btn btn-dark py-3 px-4"><i class="fa fa-search me-2"></i>Voir nos biens</a>
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