@extends('layouts.app')

@section('title', 'ArtDecoNavigator - Votre Agence Immobilière')

@section('content')

    <div class="container-fluid bg-white p-0">
       


        <!-- Header Start -->
        <div class="container-fluid header bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4">Nos agents immobiliers</h1> 
                        <nav aria-label="breadcrumb animated fadeIn">
                        <ol class="breadcrumb text-uppercase">
                            <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="#">Biens</a></li>
                            <li class="breadcrumb-item text-body active" aria-current="page">Nos agents</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 animated fadeIn">
                    <img class="img-fluid" src="img/header.jpg" alt="Agents immobiliers ArtDecoNavigator">
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Team Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Notre équipe d'experts</h1>
                    <p>Nos agents immobiliers sont des professionnels passionnés qui mettent leur expertise à votre service pour concrétiser vos projets dans les meilleures conditions.</p>
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
                                <small>Directrice générale & Conseillère senior</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="fa fa-phone me-1"></i> +33 1 23 45 67 90</small><br>
                                    <small class="text-primary"><i class="fa fa-envelope me-1"></i> sophie@ArtDecoNavigator.fr</small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark me-1">Appartements</span>
                                    <span class="badge bg-light text-dark me-1">Cotonou</span>
                                    <span class="badge bg-light text-dark">Luxury</span>
                                </div>
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
                                <small>Conseiller immobilier senior</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="fa fa-phone me-1"></i> +33 1 23 45 67 91</small><br>
                                    <small class="text-primary"><i class="fa fa-envelope me-1"></i> thomas@ArtDecoNavigator.fr</small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark me-1">Maisons</span>
                                    <span class="badge bg-light text-dark me-1">Banlieue</span>
                                    <span class="badge bg-light text-dark">Investissement</span>
                                </div>
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
                                <small>Spécialiste biens de prestige</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="fa fa-phone me-1"></i> +33 1 23 45 67 92</small><br>
                                    <small class="text-primary"><i class="fa fa-envelope me-1"></i> emilie@ArtDecoNavigator.fr</small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark me-1">Villas</span>
                                    <span class="badge bg-light text-dark me-1">Côte d'Azur</span>
                                    <span class="badge bg-light text-dark">Prestige</span>
                                </div>
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
                                <small>Expert locations & gestion locative</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="fa fa-phone me-1"></i> +33 1 23 45 67 93</small><br>
                                    <small class="text-primary"><i class="fa fa-envelope me-1"></i> julien@ArtDecoNavigator.fr</small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark me-1">Location</span>
                                    <span class="badge bg-light text-dark me-1">Gestion</span>
                                    <span class="badge bg-light text-dark">Commerciaux</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-1.jpg" alt="Caroline Petit">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Caroline Petit</h5>
                                <small>Conseillère neuf & investissement</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="fa fa-phone me-1"></i> +33 1 23 45 67 94</small><br>
                                    <small class="text-primary"><i class="fa fa-envelope me-1"></i> caroline@ArtDecoNavigator.fr</small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark me-1">Neuf</span>
                                    <span class="badge bg-light text-dark me-1">Pinel</span>
                                    <span class="badge bg-light text-dark">Rendement</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-2.jpg" alt="Marc Lefèvre">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Marc Lefèvre</h5>
                                <small>Spécialiste immobilier d'entreprise</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="fa fa-phone me-1"></i> +33 1 23 45 67 95</small><br>
                                    <small class="text-primary"><i class="fa fa-envelope me-1"></i> marc@ArtDecoNavigator.fr</small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark me-1">Bureaux</span>
                                    <span class="badge bg-light text-dark me-1">Commerciaux</span>
                                    <span class="badge bg-light text-dark">Entreprise</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-3.jpg" alt="Laura Bernard">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Laura Bernard</h5>
                                <small>Conseillère international</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="fa fa-phone me-1"></i> +33 1 23 45 67 96</small><br>
                                    <small class="text-primary"><i class="fa fa-envelope me-1"></i> laura@ArtDecoNavigator.fr</small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark me-1">International</span>
                                    <span class="badge bg-light text-dark me-1">Expatriés</span>
                                    <span class="badge bg-light text-dark">Investisseurs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                        <div class="team-item rounded overflow-hidden">
                            <div class="position-relative">
                                <img class="img-fluid" src="img/team-4.jpg" alt="Philippe Roux">
                                <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-square mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                            <div class="text-center p-4 mt-3">
                                <h5 class="fw-bold mb-0">Philippe Roux</h5>
                                <small>Expert juridique & fiscal</small>
                                <div class="mt-2">
                                    <small class="text-primary"><i class="fa fa-phone me-1"></i> +33 1 23 45 67 97</small><br>
                                    <small class="text-primary"><i class="fa fa-envelope me-1"></i> philippe@ArtDecoNavigator.fr</small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-light text-dark me-1">Juridique</span>
                                    <span class="badge bg-light text-dark me-1">Fiscalité</span>
                                    <span class="badge bg-light text-dark">Successions</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Team End -->

        <!-- Expertise Start -->
        <div class="container-fluid py-5 bg-light">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Nos domaines d'expertise</h1>
                    <p>Chaque agent possède une spécialisation pour vous offrir un accompagnement sur-mesure.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="d-flex align-items-start">
                            <div class="icon-square bg-white text-primary rounded-circle me-3 p-2">
                                <i class="fa fa-home fa-2x"></i>
                            </div>
                            <div>
                                <h5>Immobilier résidentiel</h5>
                                <p>Achat, vente et location d'appartements, maisons et villas pour particuliers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="d-flex align-items-start">
                            <div class="icon-square bg-white text-primary rounded-circle me-3 p-2">
                                <i class="fa fa-building fa-2x"></i>
                            </div>
                            <div>
                                <h5>Immobilier d'entreprise</h5>
                                <p>Bureaux, locaux commerciaux et industriels pour les professionnels.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-start">
                            <div class="icon-square bg-white text-primary rounded-circle me-3 p-2">
                                <i class="fa fa-chart-line fa-2x"></i>
                            </div>
                            <div>
                                <h5>Investissement & Gestion</h5>
                                <p>Rendement locatif, défiscalisation et gestion de patrimoine immobilier.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="d-flex align-items-start">
                            <div class="icon-square bg-white text-primary rounded-circle me-3 p-2">
                                <i class="fa fa-gem fa-2x"></i>
                            </div>
                            <div>
                                <h5>Biens de prestige</h5>
                                <p>Propriétés d'exception, châteaux, domaines et villas haut de gamme.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="d-flex align-items-start">
                            <div class="icon-square bg-white text-primary rounded-circle me-3 p-2">
                                <i class="fa fa-balance-scale fa-2x"></i>
                            </div>
                            <div>
                                <h5>Conseil juridique & fiscal</h5>
                                <p>Accompagnement dans les aspects légaux et fiscaux de vos transactions.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="d-flex align-items-start">
                            <div class="icon-square bg-white text-primary rounded-circle me-3 p-2">
                                <i class="fa fa-globe fa-2x"></i>
                            </div>
                            <div>
                                <h5>Clientèle internationale</h5>
                                <p>Accompagnement des expatriés et investisseurs étrangers en Bénin.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Expertise End -->

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
                                    <h1 class="mb-3">Rencontrez l'agent qui vous correspond</h1>
                                    <p>Notre équipe d'experts est disponible pour discuter de votre projet et vous orienter vers le spécialiste le plus adapté à vos besoins.</p>
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