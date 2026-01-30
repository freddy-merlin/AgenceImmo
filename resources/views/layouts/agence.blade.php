<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ArtDecoNavigator')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #586544;
            --secondary: #FF6922;
            --light: #f1f08a;
            --dark: #0E2E50;
        }
        
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
        
        .bg-primary { background-color: var(--primary); }
        .bg-secondary { background-color: var(--secondary); }
        .bg-light { background-color: var(--light); }
        .bg-dark { background-color: var(--dark); }
        .text-primary { color: var(--primary); }
        .text-secondary { color: var(--secondary); }
        .text-dark { color: var(--dark); }
        .border-primary { border-color: var(--primary); }
        .border-secondary { border-color: var(--secondary); }
        .hover\:bg-primary-light:hover { background-color: #6a7855; }
        .hover\:bg-secondary:hover { background-color: var(--secondary); }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#586544',
                        secondary: '#FF6922',
                        light: '#f1f08a',
                        dark: '#0E2E50',
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Sidebar -->
    <div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-dark to-primary text-white transform -translate-x-full lg:translate-x-0 sidebar-transition shadow-xl">
        <div class="flex flex-col h-full">
            <!-- Logo -->
            <div class="flex items-center justify-between p-4 border-b border-primary">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-secondary rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg">ArtDeco</h2>
                        <p class="text-xs text-light">Navigator</p>
                    </div>
                </a>
                <button id="closeSidebar" class="lg:hidden text-white hover:text-light">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <div class="px-3 space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary' : 'hover:bg-primary' }} text-white transition">
                        <i class="fas fa-home"></i>
                        <span>Tableau de bord</span>
                    </a>
                    
                    <div class="pt-4 pb-2 px-4">
                        <p class="text-xs font-semibold text-light uppercase">Gestion</p>
                    </div>
                    
                    <a href="{{ route('properties.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('properties.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-building"></i>
                        <span>Biens immobiliers</span>
                    </a>
                    @if ( Auth()->user()->hasRole('agence'))
                         <a href="{{ route('agents.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('agents.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-users"></i>
                        <span>Agents</span>
                    </a>
                    
                    @endif
                   
                    <a href="{{ route('owners.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('owners.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-user-tie"></i>
                        <span>Propriétaires</span>
                    </a>
                    
                    <a href="{{ route('tenants.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('tenants.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-user-friends"></i>
                        <span>Locataires</span>
                    </a>
                    
                    <a href="{{ route('contracts.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('contracts.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-file-contract"></i>
                        <span>Contrats</span>
                    </a>
                    
                    <div class="pt-4 pb-2 px-4">
                        <p class="text-xs font-semibold text-light uppercase">Financier</p>
                    </div>
                    
                    <a href="{{ route('payments.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('payments.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Paiements</span>
                        @if(isset($pendingPayments) && $pendingPayments > 0)
                            <span class="ml-auto bg-secondary text-white text-xs px-2 py-1 rounded-full">{{ $pendingPayments }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('statistics.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('statistics.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-chart-line"></i>
                        <span>Statistiques</span>
                    </a>
                    
                    <div class="pt-4 pb-2 px-4">
                        <p class="text-xs font-semibold text-light uppercase">Support</p>
                    </div>
                    
                    <a href="{{ route('agence.reclamations.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('complaints.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Réclamations</span>
                        @if(isset($activeComplaints) && $activeComplaints > 0)
                            <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">{{ $activeComplaints }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('workers.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('workers.*') ? 'bg-primary' : 'hover:bg-primary' }} transition">
                        <i class="fas fa-tools"></i>
                        <span>Ouvriers</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-primary">
                <div class="flex items-center space-x-3">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Agence+Admin' }}&background=FF6922&color=fff" alt="Profile" class="w-10 h-10 rounded-full">
                    <div class="flex-1">
                        <p class="font-medium text-sm">{{ auth()->user()->name ?? 'Admin Agence' }}</p>
                        <p class="text-xs text-light">{{ auth()->user()->email ?? 'admin@artdeco.com' }}</p>
                    </div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-light hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlay pour mobile -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Top Bar -->
        <header class="bg-white shadow-sm sticky top-0 z-30">
            <div class="flex items-center justify-between px-4 py-3">
                <div class="flex items-center space-x-4">
                    <button id="menuToggle" class="lg:hidden text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-dark">@yield('header-title', 'Tableau de bord')</h1>
                        <p class="text-sm text-gray-500">@yield('header-subtitle', 'Vue d\'ensemble de votre agence')</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <button class="relative text-gray-600 hover:text-gray-900">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-secondary text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">@yield('notifications-count', 0)</span>
                    </button>
                    <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-cog text-xl"></i>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-4 lg:p-6">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menuToggle');
        const closeSidebar = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebarFunc() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (menuToggle) {
            menuToggle.addEventListener('click', openSidebar);
        }
        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunc);
        }
        if (overlay) {
            overlay.addEventListener('click', closeSidebarFunc);
        }

        // Close sidebar on window resize if mobile
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebarFunc();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>