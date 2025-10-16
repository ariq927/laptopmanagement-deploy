<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PeminjamanNewController;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\Api\PegawaiController;
use App\Exports\LaptopsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\tables\Basic as TablesBasic;


// ========================
// LOGIN & REGISTER
// ========================

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ========================
// USER DASHBOARD
// ========================
Route::get('/', [UserDashboardController::class, 'index'])->middleware('auth')->name('dashboard-analytics');
Route::get('/dashboard', [UserDashboardController::class, 'index'])->middleware('auth')->name('user.dashboard');


// ========================
// LAYOUTS & PAGES
// ========================
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('account.setting');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
Route::put('/account/update', [AccountSettingsAccount::class, 'update'])->name('account.update');



// authentication views
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');


// ========================
// USER INTERFACE
// ========================
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');


// ========================
// FORMS
// ========================
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');
Route::view('/form-layout-horizontal', 'content.form-layout.form-layouts-horizontal')->name('form.horizontal');


// ========================
// PEMINJAMAN (User)
// ========================
Route::get('/peminjaman', [PeminjamanNewController::class, 'index'])->name('peminjaman.index');
Route::get('/peminjaman/create/{id}', [PeminjamanNewController::class, 'create'])->name('peminjaman.create');
Route::delete('/peminjaman/selesai/{id}', [PeminjamanNewController::class, 'selesai'])->name('peminjaman.selesai');
Route::get('/tables/basic', [PeminjamanNewController::class, 'index'])->middleware('auth')->name('tables.basic');


// ========================
// ADMIN LOGIN & DASHBOARD
// ========================
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');

Route::get('/admin/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register.post');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/summary', [AdminController::class, 'summary'])->name('admin.summary');
    Route::get('/analytics', [AdminController::class, 'loanAnalytics'])->name('admin.analytics');

    // CRUD Laptop
    Route::post('/laptop', [AdminController::class, 'storeLaptop'])->name('admin.laptop.store');
    Route::delete('/laptop/{id}', [AdminController::class, 'destroyLaptop'])->name('admin.laptop.destroy');

    Route::delete('/peminjaman/{id}', [AdminController::class, 'removeUserLoan'])->name('admin.peminjaman.remove');
});

// CRUD Laptop (user scope)
Route::get('/laptop/{id}/edit', [AdminController::class, 'editLaptop'])->name('laptop.edit');
Route::put('/laptop/{laptop}', [LaptopController::class, 'update'])->name('laptop.update');
Route::delete('/laptop/{id}', [AdminController::class, 'destroyLaptop'])->name('laptop.destroy');
Route::get('/tables/laptop', [AdminController::class, 'index'])->middleware('auth')->name('laptop.index');
Route::get('/peminjaman/create/{id}', [PeminjamanNewController::class, 'create'])->name('peminjaman.create');

Route::get('/login', [LoginBasic::class, 'index'])->name('login');
Route::post('/login', [LoginBasic::class, 'login'])->name('login.post');

Route::get('/', [UserDashboardController::class, 'index'])->middleware('auth')->name('dashboard-analytics');

Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::post('/login', [AuthController::class, 'login'])->name('auth-login-basic-post');

// Route::middleware(['auth'])->group(function () {
    Route::get('/laptop/create', [LaptopController::class, 'create'])->name('laptop.create');
    Route::post('/laptop', [LaptopController::class, 'store'])->name('laptop.store');
    Route::post('/peminjaman/store', [PeminjamanNewController::class, 'store'])->name('peminjaman.store');
// });


Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');

Route::get('/laptop/create', [LaptopController::class, 'create'])->name('laptop.create');
Route::post('/laptop', [LaptopController::class, 'store'])->name('laptop.store');

Route::get('/cari/peminjam', [PeminjamanNewController::class, 'cari'])->name('cari.nama');

//Arsip Laptop
Route::patch('/laptop/{id}/archive', [LaptopController::class, 'archive'])->name('laptop.archive');
Route::patch('/laptop/{id}/restore', [LaptopController::class, 'restore'])->name('laptop.restore');
Route::get('/arsip/laptop', [LaptopController::class, 'arsipLaptop'])->middleware('auth')->name('arsip.tabel');

// buat laporan 
Route::get('/laporan', function() {
    return view('content.reports.laporan');
})->middleware('auth')->name('laporan');
Route::get('/laporan/export', [ReportController::class, 'export'])->name('laporan.export');
Route::get('/laporan/preview-pdf', [ReportController::class, 'previewPDF'])->name('laporan.previewPDF');


Route::get('/ldap-test', function () {
    try {
        $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
            ->post(env('LDAP_API_URL'), [
                'username' => 'test',
                'password' => 'test',
            ]);

        return response()->json([
            'ok' => true,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'ok' => false,
            'error' => $e->getMessage(),
        ], 500);
    }
});
