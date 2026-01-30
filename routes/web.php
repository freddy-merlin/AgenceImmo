<?php

use Illuminate\Support\Facades\Route;
 
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\SettingsController; 
use App\Http\Controllers\HomeController; 

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});

        

 
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/property-list', function () {
    return view('property-list');
})->name('property.list');

Route::get('/property-type', function () {
    return view('property-type');
})->name('property.type');

Route::get('/property-agent', function () {
    return view('property-agent');
})->name('property.agent');

Route::get('/testimonial', function () {
    return view('testimonial');
})->name('testimonial');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Pages supplémentaires
Route::get('/legal', function () {
    return view('legal');
})->name('legal');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/help', function () {
    return view('help');
})->name('help');

Route::get('/cookies', function () {
    return view('cookies');
})->name('cookies');

 
 

 





 
// Routes Agences 
Route::middleware(['auth', 'agence'])->group(function () {


    

 
                Route::prefix('properties')->name('properties.')->group(function () {
                    Route::get('/', [PropertyController::class, 'index'])->name('index');
                    Route::get('/create', [PropertyController::class, 'create'])->name('create');   
                    Route::post('/store', [PropertyController::class, 'store'])->name('store'); 
                    Route::get('/export', [PropertyController::class, 'export'])->name('export');
                    Route::get('/{id}', [PropertyController::class, 'show'])->name('show');
                    Route::get('/{id}/edit', [PropertyController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [PropertyController::class, 'update'])->name('update');
                    Route::delete('/{id}', [PropertyController::class, 'destroy'])->name('destroy'); // Changé de GET à DELETE
                });
            

                Route::prefix('agents')->name('agents.')->group(function () {
                Route::get('/', [AgentController::class, 'index'])->name('index');
                Route::get('/create', [AgentController::class, 'create'])->name('create');
                Route::post('/', [AgentController::class, 'store'])->name('store');
                Route::get('/{id}', [AgentController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [AgentController::class, 'edit'])->name('edit');
                Route::put('/{id}', [AgentController::class, 'update'])->name('update');
                Route::delete('/{id}', [AgentController::class, 'destroy'])->name('destroy');
                
                Route::get('/{id}/biens', [AgentController::class, 'biens'])->name('biens');
            });

            

                Route::prefix('owners')->name('owners.')->group(function () {
                    Route::get('/', [OwnerController::class, 'index'])->name('index');
                    Route::get('/create', [OwnerController::class, 'create'])->name('create'); 
                    Route::get('/{id}', [OwnerController::class, 'show'])->name('show');
                Route::post('/', [OwnerController::class, 'store'])->name('store');

                    Route::get('/{id}/edit', [OwnerController::class, 'edit'])->name('edit');
                    Route::put('/{owner}', [OwnerController::class, 'update'])->name('update');
                Route::delete('/{id}', [OwnerControlle::class, 'destroy'])->name('destroy');
                

                });


                
                
                // Routes pour les locataires
            Route::prefix('tenants')->name('tenants.')->group(function () {
                Route::get('/', [TenantController::class, 'index'])->name('index');
                Route::get('/create', [TenantController::class, 'create'])->name('create');
                Route::post('/', [TenantController::class, 'store'])->name('store');
                Route::get('/{tenant}', [TenantController::class, 'show'])->name('show');
                Route::get('/{tenant}/edit', [TenantController::class, 'edit'])->name('edit');
                Route::put('/{tenant}', [TenantController::class, 'update'])->name('update');
                Route::delete('/{tenant}', [TenantController::class, 'destroy'])->name('destroy');
            });

            // Routes pour les contrats
            Route::prefix('contracts')->name('contracts.')->group(function () {
                Route::get('/', [ContractController::class, 'index'])->name('index');
                Route::get('/create', [ContractController::class, 'create'])->name('create');
                Route::post('/', [ContractController::class, 'store'])->name('store');
                Route::get('/{contract}', [ContractController::class, 'show'])->name('show');
                Route::get('/{contract}/edit', [ContractController::class, 'edit'])->name('edit');
                Route::put('/{contract}', [ContractController::class, 'update'])->name('update');
                Route::delete('/{contract}', [ContractController::class, 'destroy'])->name('destroy');
                
            
                Route::post('/{contract}/renew', [ContractController::class, 'renew'])->name('renew');
            // Route::post('/{contract}/terminate', [ContractController::class, 'terminate'])->name('terminate');
                Route::get('/{contract}/download', [ContractController::class, 'download'])->name('download');

            // Routes pour la résiliation de contrats
            Route::get('/{id}/terminate', [ContractController::class, 'showTerminateForm'])
                ->name('terminate.form');
            

                Route::post('/{id}/terminate', [ContractController::class, 'terminate'])
                ->name('terminate.valide');


            
            });

                // Routes pour les ouvriers
            
                Route::resource('ouvriers', WorkerController::class);



            //  Route::get('/ouvriers', [WorkerController::class, 'index'])->name('ouvriers.index');

                
                Route::post('/ouvriers/assigner', [WorkerController::class, 'assigner'])
                    ->name('ouvriers.assigner');
                
                Route::delete('/assignations/{id}', [WorkerController::class, 'retirerBien'])
                    ->name('assignations.destroy');

                Route::get('ouvriers/{bien}', [WorkerController::class, 'show'])->name('biens.show');



                // Agents
                Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
                
                // Propriétaires
                Route::get('/owners', [OwnerController::class, 'index'])->name('owners.index');
                
                // Locataires
            // Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
                //Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
                
                // Contrats
                Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
                Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
                
                // Paiements
                Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
                Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
                Route::get('/payments/unpaid', [PaymentController::class, 'unpaid'])->name('payments.unpaid');
                
                // Statistiques
                Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
                
                // Réclamations
            
                Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');

            Route::get('complaints', [ComplaintController::class, 'index'])->name('agence.reclamations.index');
            Route::get('complaints/create', [ComplaintController::class, 'create'])->name('agence.reclamations.create');
                Route::get('/{reclamation}/show', [ComplaintController::class, 'show'])->name('agence.reclamations.show');
                Route::get('/{reclamation}/edit', [ComplaintController::class, 'edit'])->name('agence.reclamations.edit');
                Route::put('/{reclamation}', [ComplaintController::class, 'update'])->name('agence.reclamations.update');
                Route::delete('/{contract}', [ComplaintController::class, 'destroy'])->name('agence.reclamations.destroy');
                



                // Routes pour les réclamations de l'agence
            Route::prefix('agence')->group(function () {
            
                Route::resource('reclamations', ReclamationController::class);
                Route::post('/reclamations/{reclamation}/assigner-ouvrier', [ComplaintController::class, 'assignerOuvrier'])->name('agence.reclamations.assigner-ouvrier');
                Route::post('/reclamations/{reclamation}/changer-statut', [ComplaintController::class, 'changerStatut'])->name('agence.reclamations.changer-statut');
                Route::get('/reclamations/{reclamation}/download-photo/{index}', [ComplaintController::class, 'downloadPhoto'])->name('agence.reclamations.download-photo');
                Route::delete('/reclamations/{reclamation}/delete-photo/{index}', [ComplaintController::class, 'deletePhoto'])->name('agence.reclamations.delete-photo');
                Route::get('/reclamations/rapport', [ReclamationController::class, 'rapport'])->name('agence.reclamations.rapport');
            });


                
                // Ouvriers
                Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
                
                // Paramètres
                Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');



    
});


// Routes Agences 
Route::middleware(['auth' ])->group(function () {

    Route::prefix('properties')->name('properties.')->group(function () {
                    Route::get('/', [PropertyController::class, 'index'])->name('index');
                    Route::get('/create', [PropertyController::class, 'create'])->name('create');   
                    Route::post('/store', [PropertyController::class, 'store'])->name('store'); 
                    Route::get('/export', [PropertyController::class, 'export'])->name('export');
                    Route::get('/{id}', [PropertyController::class, 'show'])->name('show');
                    Route::get('/{id}/edit', [PropertyController::class, 'edit'])->name('edit');
                    Route::put('/{id}', [PropertyController::class, 'update'])->name('update');
                    Route::delete('/{id}', [PropertyController::class, 'destroy'])->name('destroy'); // Changé de GET à DELETE
                });
            

                Route::prefix('agents')->name('agents.')->group(function () {
                Route::get('/', [AgentController::class, 'index'])->name('index');
                Route::get('/create', [AgentController::class, 'create'])->name('create');
                Route::post('/', [AgentController::class, 'store'])->name('store');
                Route::get('/{id}', [AgentController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [AgentController::class, 'edit'])->name('edit');
                Route::put('/{id}', [AgentController::class, 'update'])->name('update');
                Route::delete('/{id}', [AgentController::class, 'destroy'])->name('destroy');
                
                Route::get('/{id}/biens', [AgentController::class, 'biens'])->name('biens');
            });

            

                Route::prefix('owners')->name('owners.')->group(function () {
                    Route::get('/', [OwnerController::class, 'index'])->name('index');
                    Route::get('/create', [OwnerController::class, 'create'])->name('create'); 
                    Route::get('/{id}', [OwnerController::class, 'show'])->name('show');
                Route::post('/', [OwnerController::class, 'store'])->name('store');

                    Route::get('/{id}/edit', [OwnerController::class, 'edit'])->name('edit');
                    Route::put('/{owner}', [OwnerController::class, 'update'])->name('update');
                Route::delete('/{id}', [OwnerControlle::class, 'destroy'])->name('destroy');
                

                });


                
                
                // Routes pour les locataires
            Route::prefix('tenants')->name('tenants.')->group(function () {
                Route::get('/', [TenantController::class, 'index'])->name('index');
                Route::get('/create', [TenantController::class, 'create'])->name('create');
                Route::post('/', [TenantController::class, 'store'])->name('store');
                Route::get('/{tenant}', [TenantController::class, 'show'])->name('show');
                Route::get('/{tenant}/edit', [TenantController::class, 'edit'])->name('edit');
                Route::put('/{tenant}', [TenantController::class, 'update'])->name('update');
                Route::delete('/{tenant}', [TenantController::class, 'destroy'])->name('destroy');
            });

            // Routes pour les contrats
            Route::prefix('contracts')->name('contracts.')->group(function () {
                Route::get('/', [ContractController::class, 'index'])->name('index');
                Route::get('/create', [ContractController::class, 'create'])->name('create');
                Route::post('/', [ContractController::class, 'store'])->name('store');
                Route::get('/{contract}', [ContractController::class, 'show'])->name('show');
                Route::get('/{contract}/edit', [ContractController::class, 'edit'])->name('edit');
                Route::put('/{contract}', [ContractController::class, 'update'])->name('update');
                Route::delete('/{contract}', [ContractController::class, 'destroy'])->name('destroy');
                
            
                Route::post('/{contract}/renew', [ContractController::class, 'renew'])->name('renew');
            // Route::post('/{contract}/terminate', [ContractController::class, 'terminate'])->name('terminate');
                Route::get('/{contract}/download', [ContractController::class, 'download'])->name('download');

            // Routes pour la résiliation de contrats
            Route::get('/{id}/terminate', [ContractController::class, 'showTerminateForm'])
                ->name('terminate.form');
            

                Route::post('/{id}/terminate', [ContractController::class, 'terminate'])
                ->name('terminate.valide');


            
            });

                // Routes pour les ouvriers
            
                Route::resource('ouvriers', WorkerController::class);



            //  Route::get('/ouvriers', [WorkerController::class, 'index'])->name('ouvriers.index');

                
                Route::post('/ouvriers/assigner', [WorkerController::class, 'assigner'])
                    ->name('ouvriers.assigner');
                
                Route::delete('/assignations/{id}', [WorkerController::class, 'retirerBien'])
                    ->name('assignations.destroy');

                Route::get('ouvriers/{bien}', [WorkerController::class, 'show'])->name('biens.show');



                // Agents
                Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
                
                // Propriétaires
                Route::get('/owners', [OwnerController::class, 'index'])->name('owners.index');
                
                // Locataires
            // Route::get('/tenants', [TenantController::class, 'index'])->name('tenants.index');
                //Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
                
                // Contrats
                Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
                Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
                
                // Paiements
                Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
                Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
                Route::get('/payments/unpaid', [PaymentController::class, 'unpaid'])->name('payments.unpaid');
                
                // Statistiques
                Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
                
                // Réclamations
            
                Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');

            Route::get('complaints', [ComplaintController::class, 'index'])->name('agence.reclamations.index');
            Route::get('complaints/create', [ComplaintController::class, 'create'])->name('agence.reclamations.create');
                Route::get('/{reclamation}/show', [ComplaintController::class, 'show'])->name('agence.reclamations.show');
                Route::get('/{reclamation}/edit', [ComplaintController::class, 'edit'])->name('agence.reclamations.edit');
                Route::put('/{reclamation}', [ComplaintController::class, 'update'])->name('agence.reclamations.update');
                Route::delete('/{contract}', [ComplaintController::class, 'destroy'])->name('agence.reclamations.destroy');
                



                // Routes pour les réclamations de l'agence
            Route::prefix('agence')->group(function () {
            
                Route::resource('reclamations', ReclamationController::class);
                Route::post('/reclamations/{reclamation}/assigner-ouvrier', [ComplaintController::class, 'assignerOuvrier'])->name('agence.reclamations.assigner-ouvrier');
                Route::post('/reclamations/{reclamation}/changer-statut', [ComplaintController::class, 'changerStatut'])->name('agence.reclamations.changer-statut');
                Route::get('/reclamations/{reclamation}/download-photo/{index}', [ComplaintController::class, 'downloadPhoto'])->name('agence.reclamations.download-photo');
                Route::delete('/reclamations/{reclamation}/delete-photo/{index}', [ComplaintController::class, 'deletePhoto'])->name('agence.reclamations.delete-photo');
                Route::get('/reclamations/rapport', [ReclamationController::class, 'rapport'])->name('agence.reclamations.rapport');
            });


                
                // Ouvriers
                Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
                
                // Paramètres
                Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');



    
});



 