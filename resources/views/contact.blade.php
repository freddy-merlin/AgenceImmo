@extends('layouts.app')

@section('title', 'ArtDecoNavigator - Votre Agence Immobilière')

@section('content')

    <div class="container-fluid bg-white p-0">
      


        <!-- Header Start -->
        <div class="container-fluid header bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4">Contactez-nous</h1> 
                        <nav aria-label="breadcrumb animated fadeIn">
                        <ol class="breadcrumb text-uppercase">
                            <li class="breadcrumb-item"><a href="index.html">Accueil</a></li>
                            <li class="breadcrumb-item"><a href="#">Pages</a></li>
                            <li class="breadcrumb-item text-body active" aria-current="page">Contact</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 animated fadeIn">
                    <img class="img-fluid" src="img/header.jpg" alt="Contact ArtDecoNavigator">
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Contact Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Contactez notre agence</h1>
                    <p>Notre équipe d'experts immobiliers est à votre disposition pour répondre à toutes vos questions et vous accompagner dans votre projet.</p>
                </div>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="row gy-4">
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                                <div class="bg-light rounded p-3">
                                    <div class="d-flex align-items-center bg-white rounded p-3" style="border: 1px dashed rgba(0, 185, 142, .3)">
                                        <div class="icon me-3" style="width: 45px; height: 45px;">
                                            <i class="fa fa-map-marker-alt text-primary"></i>
                                        </div>
                                        <span>123 Avenue des  steimez</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                                <div class="bg-light rounded p-3">
                                    <div class="d-flex align-items-center bg-white rounded p-3" style="border: 1px dashed rgba(0, 185, 142, .3)">
                                        <div class="icon me-3" style="width: 45px; height: 45px;">
                                            <i class="fa fa-envelope-open text-primary"></i>
                                        </div>
                                        <span>contact@ArtDecoNavigator.fr</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                                <div class="bg-light rounded p-3">
                                    <div class="d-flex align-items-center bg-white rounded p-3" style="border: 1px dashed rgba(0, 185, 142, .3)">
                                        <div class="icon me-3" style="width: 45px; height: 45px;">
                                            <i class="fa fa-phone-alt text-primary"></i>
                                        </div>
                                        <span>+229 XX XX XX XX XX</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <iframe class="position-relative rounded w-100 h-100"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9916256937595!2d2.292292615509614!3d48.85837007928746!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fe99c2b3b0b%3A0x7f0f6f8b9d7b0b0b!2sChamps-%C3%89lys%C3%A9es%2C%2075008%20Cotonou!5e0!3m2!1sfr!2sfr!4v1603794290143!5m2!1sfr!2sfr"
                            frameborder="0" style="min-height: 400px; border:0;" allowfullscreen="" aria-hidden="false"
                            tabindex="0"></iframe>
                    </div>
                    <div class="col-md-6">
                        <div class="wow fadeInUp" data-wow-delay="0.5s">
                            <h4 class="mb-4">Envoyez-nous un message</h4>
                            <p>Remplissez ce formulaire pour toute demande d'information, d'estimation ou de rendez-vous. Notre équipe vous répondra dans les plus brefs délais.</p>
                            <form>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" placeholder="Votre nom">
                                            <label for="name">Votre nom</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="email" placeholder="Votre email">
                                            <label for="email">Votre email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="phone" placeholder="Votre téléphone">
                                            <label for="phone">Votre téléphone</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select class="form-select" id="subject">
                                                <option selected>Sélectionnez un sujet</option>
                                                <option value="estimation">Estimation gratuite</option>
                                                <option value="visite">Visite d'un bien</option>
                                                <option value="location">Information location</option>
                                                <option value="vente">Information vente</option>
                                                <option value="autre">Autre demande</option>
                                            </select>
                                            <label for="subject">Sujet de votre message</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea class="form-control" placeholder="Laissez votre message ici" id="message" style="height: 150px"></textarea>
                                            <label for="message">Votre message</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newsletter">
                                            <label class="form-check-label" for="newsletter">
                                                Je souhaite recevoir les offres et actualités d'ArtDecoNavigator
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit">Envoyer le message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact End -->


        <!-- Horaires Start -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3">Nos horaires d'ouverture</h1>
                    <p>Notre agence vous accueille aux horaires suivants. Pour un rendez-vous en dehors de ces créneaux, n'hésitez pas à nous contacter.</p>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="table-responsive wow fadeInUp" data-wow-delay="0.3s">
                            <table class="table table-bordered text-center">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Jour</th>
                                        <th>Horaires</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Lundi - Vendredi</td>
                                        <td>9h00 - 19h00</td>
                                    </tr>
                                    <tr>
                                        <td>Samedi</td>
                                        <td>10h00 - 18h00</td>
                                    </tr>
                                    <tr>
                                        <td>Dimanche</td>
                                        <td>Sur rendez-vous</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Horaires End -->


      


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