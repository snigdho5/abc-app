<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', 'AdminController@index')->name('admin.dashboard');

Auth::routes();

Route::POST('admin/auth', 'Auth\LoginController@login')->name('LoginProcess');

Route::prefix('admin')->group(function () {
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::get('dashboard', 'AdminController@index')->name('admin.dashboard');
    Route::get('login', 'Auth\Admin\LoginController@login')->name('admin.auth.login');
    Route::post('login', 'Auth\Admin\LoginController@loginAdmin')->name('admin.auth.loginAdmin');
    Route::post('logout', 'Auth\Admin\LoginController@logout')->name('admin.auth.logout');

    // abc routes
    Route::POST('add-new-location', 'LocationController@AddNewLocation')->name('location.register');
    Route::get('view-location', 'LocationController@Locations')->name('location.manage');
    Route::POST('edit-location-ajax', 'LocationController@GetLocationData')->name('location.data');
    Route::post('edit-location', 'LocationController@EditLocation')->name('location.edit');
    Route::get('delete-location/{id}', 'LocationController@DeleteLocation')->name('location.delete');

    Route::POST('add-new-centre', 'CentreController@AddNewCentre')->name('centre.register');
    Route::get('view-centre', 'CentreController@Centres')->name('centre.manage');
    Route::POST('edit-centre-ajax', 'CentreController@GetCentreData')->name('centre.data');
    Route::post('edit-centre', 'CentreController@EditCentre')->name('centre.edit');
    Route::get('delete-centre/{id}', 'CentreController@DeleteCentre')->name('centre.delete');

    Route::POST('add-new-category', 'CategoryController@AddNewCategory')->name('category.register');
    Route::get('view-category', 'CategoryController@Categories')->name('category.manage');
    Route::POST('edit-category-ajax', 'CategoryController@GetCategoryData')->name('category.data');
    Route::post('edit-category', 'CategoryController@EditCategory')->name('category.edit');
    Route::get('delete-category/{id}', 'CategoryController@DeleteCategory')->name('category.delete');

    Route::POST('check-flagcategory-ajax', 'CategoryController@GetCatFlagData');

    Route::POST('add-new-msinfo', 'MsinfoController@AddNewMsinfo')->name('msinfo.register');
    Route::get('view-msinfo', 'MsinfoController@Msinfos')->name('msinfo.manage');
    Route::POST('edit-msinfo-ajax', 'MsinfoController@GetMsinfoData')->name('msinfo.data');
    Route::post('edit-msinfo', 'MsinfoController@EditMsinfo')->name('msinfo.edit');
    Route::get('delete-msinfo/{id}', 'MsinfoController@DeleteMsinfo')->name('msinfo.delete');


    Route::POST('add-new-meetingroom', 'MeetingroomController@AddNewMeetingroom')->name('meetingroom.register');
    Route::get('view-meetingroom', 'MeetingroomController@Meetingrooms')->name('meetingroom.manage');
//    Route::POST('edit-meetingroom-ajax', 'MeetingroomController@GetMeetingroomData')->name('meetingroom.data');
    Route::post('edit-meetingroom1', 'MeetingroomController@EditMeetingroom')->name('meetingroom.edit');
    Route::get('delete-meetingroom/{id}', 'MeetingroomController@DeleteMeetingroom')->name('meetingroom.delete');

    Route::get('get-meetingroom-info/{id}', 'MeetingroomController@GetMeetingroomData')->name('meetingroom.data');


    Route::POST('add-new-vopackage', 'VOPackagesController@AddNewVOPackage')->name('vopackage.register');
    Route::get('view-vopackage', 'VOPackagesController@VOPackages')->name('vopackage.manage');
    Route::POST('edit-vopackage-ajax', 'VOPackagesController@GetVOPackageData')->name('vopackage.data');
    Route::post('edit-vopackage', 'VOPackagesController@EditVOPackage')->name('vopackage.edit');
    Route::get('delete-vopackage/{id}', 'VOPackagesController@DeleteVOPackage')->name('vopackage.delete');

    Route::POST('add-new-news', 'NewsController@AddNewNews')->name('news.register');
    Route::get('view-news', 'NewsController@News')->name('news.manage');
    Route::POST('edit-news-ajax', 'NewsController@GetNewsData')->name('news.data');
    Route::post('edit-news', 'NewsController@EditNews')->name('news.edit');
    Route::get('delete-news/{id}', 'NewsController@DeleteNews')->name('news.delete');


    Route::POST('add-new-tax', 'TaxController@AddNewTax')->name('tax.register');
    Route::get('view-tax', 'TaxController@Taxes')->name('tax.manage');
    Route::POST('edit-tax-ajax', 'TaxController@GetTaxData')->name('tax.data');
    Route::post('edit-tax', 'TaxController@EditTax')->name('tax.edit');
    Route::get('delete-tax/{id}', 'TaxController@DeleteTax')->name('tax.delete');


    Route::get('view-post', 'PostController@Posts')->name('post.manage');
    Route::POST('edit-post-ajax', 'PostController@GetPostData')->name('post.data');
    Route::post('edit-post', 'PostController@EditPost')->name('post.edit');
    Route::get('delete-post/{id}', 'PostController@DeletePost')->name('post.delete');

    Route::POST('add-new-customer', 'CustomerController@AddNewCustomer')->name('customer.register');
    Route::POST('add-customer-ajax', 'CustomerController@AddNewCustomerAjax');
    Route::POST('edit-customer', 'CustomerController@EditCustomer')->name('customer.edit');
    Route::get('view-customer', 'CustomerController@Customers')->name('customer.manage');
    Route::post('edit-customer-ajax', 'CustomerController@GetCustomerData')->name('customer.edit');
    Route::get('del-customer/{id}', 'CustomerController@DeleteCustomer')->name('customer.del');
    Route::post('search-customer', 'CustomerController@SearchCustomer')->name('customer.search');

    Route::POST('getconfig-data-ajax', 'MeetingroomController@GetConfigData');

    Route::POST('saveconfig-data-ajax', 'MeetingroomController@SaveConfigData');


    Route::get('view-scustomer', 'CustomerController@SCustomers')->name('scustomer.manage');
    Route::get('change-status-scustomer/{id}', 'CustomerController@SCustomerChangeStatus')->name('scustomer.changestatus');



    Route::POST('add-new-booking', 'BookingController@AddNewBooking')->name('booking.register');
    Route::get('view-booking', 'BookingController@Bookings')->name('booking.manage');
    // Route::POST('edit-booking-ajax', 'BookingController@GetBookingData')->name('booking.data');
    Route::post('edit-booking', 'BookingController@EditBooking')->name('booking.edit');
    Route::get('delete-booking/{id}', 'BookingController@DeleteBooking')->name('booking.delete');

    Route::get('get-booking-info/{id}', 'BookingController@GetBookingData')->name('booking.data');
    Route::get('get-bookingdetail-info/{id}', 'BookingController@GetBookingDetailsData')->name('bookingdetails.data');


    Route::get('view-cbooking', 'BookingController@CBookings')->name('cbooking.manage');



    Route::POST('get-centrebyloc-ajax', 'BookingController@GetCentreData');
    Route::post('search-customer', 'CustomerController@SearchCustomer')->name('customer.search');

    Route::post('check-cobooking-available-ajax', 'BookingController@CheckCoBookingAvailable');

    Route::POST('add-new-ctext', 'CTextController@AddNewCText')->name('ctext.register');
    Route::get('view-ctext', 'CTextController@CTexts')->name('ctext.manage');
    Route::POST('edit-ctext-ajax', 'CTextController@GetCTextData')->name('ctext.data');
    Route::post('edit-ctext', 'CTextController@EditCText')->name('ctext.edit');
    Route::get('delete-ctext/{id}', 'CTextController@DeleteCText')->name('ctext.delete');

    Route::ANY('change-password', 'AdminController@ChangePassword')->name('admin.changePwd');



    Route::get('view-offer', 'OfferController@Offers')->name('offer.manage');
    Route::POST('add-new-offer', 'OfferController@AddNewOffer')->name('offer.register');
    Route::POST('edit-offer-ajax', 'OfferController@GetOfferData')->name('offer.data');
    Route::post('edit-offer', 'OfferController@EditOffer')->name('offer.edit');
    Route::get('delete-offer/{id}', 'OfferController@DeleteOffer')->name('offer.delete');


    Route::get('view-introdata', 'IntroDataController@IntroDatas')->name('introdata.manage');
    Route::POST('add-new-introdata', 'IntroDataController@AddNewIntroData')->name('introdata.register');
    Route::POST('edit-introdata-ajax', 'IntroDataController@GetIntroData')->name('introdata.data');
    Route::post('edit-introdata', 'IntroDataController@EditIntroData')->name('introdata.edit');
    Route::get('delete-introdata/{id}', 'IntroDataController@DeleteIntroData')->name('introdata.delete');


    Route::get('view-virtualtour', 'VirtualTourController@VirtualTours')->name('virtualtour.manage');
    Route::POST('add-new-virtualtour', 'VirtualTourController@AddNewVirtualTour')->name('virtualtour.register');
    Route::POST('edit-virtualtour-ajax', 'VirtualTourController@GetVirtualTourData')->name('virtualtour.data');
    Route::post('edit-virtualtour', 'VirtualTourController@EditVirtualTour')->name('virtualtour.edit');
    Route::get('delete-virtualtour/{id}', 'VirtualTourController@DeleteVirtualTour')->name('virtualtour.delete');

    Route::POST('add-new-discount', 'DiscountController@AddNewDiscount')->name('discount.register');
    Route::POST('edit-discount-ajax', 'DiscountController@GetDiscountData')->name('discount.data');
    Route::get('view-discount', 'DiscountController@Discounts')->name('discount.manage');
    Route::post('edit-discount', 'DiscountController@EditDiscount')->name('discount.edit');
    Route::get('delete-discount/{id}', 'DiscountController@DeleteDiscount')->name('discount.delete');

    Route::get('get-discount-info/{id}', 'DiscountController@GetDiscountDetails')->name('discount.detail');

    Route::POST('add-new-applink', 'SendLinkController@SendLink')->name('applink.register');
    Route::get('view-applink', 'SendLinkController@LinkDetails')->name('applink.manage');
    Route::get('delete-applink/{id}', 'SendLinkController@DeleteLink')->name('applink.delete');

    Route::POST('add-new-centreloc', 'SendLinkController@SendCentreLink')->name('centreloc.register');
    Route::get('view-centreloc', 'SendLinkController@CentreLinkDetails')->name('centreloc.manage');
    Route::get('delete-centreloc/{id}', 'SendLinkController@DeleteCentreLink')->name('centreloc.delete');

    Route::POST('add-new-emailmatrix', 'EmailMatrixController@AddNewEmailMatrix')->name('emailmatrix.register');
    Route::POST('edit-emailmatrix-ajax', 'EmailMatrixController@GetEmailMatrixData')->name('emailmatrix.data');
    Route::get('view-emailmatrix', 'EmailMatrixController@EmailMatrixs')->name('emailmatrix.manage');
    Route::post('edit-emailmatrix', 'EmailMatrixController@EditEmailMatrix')->name('emailmatrix.edit');
    Route::get('delete-emailmatrix/{id}', 'EmailMatrixController@DeleteEmailMatrix')->name('emailmatrix.delete');

    Route::POST('get-emailmatrixdata-ajax', 'EmailMatrixController@GetEmailMatrixFirstData');


    Route::POST('add-new-company', 'CompanyController@AddNewCompany')->name('company.register');
    Route::POST('edit-company-ajax', 'CompanyController@GetCompanyData')->name('company.data');
    Route::get('view-company', 'CompanyController@Company')->name('company.manage');
    Route::post('edit-company', 'CompanyController@EditCompany')->name('company.edit');
    Route::get('delete-company/{id}', 'CompanyController@DeleteCompany')->name('company.delete');


    Route::POST('add-new-companyclient', 'CompanyClientController@AddNewCompanyClient')->name('companyclient.register');
    Route::POST('edit-companyclient-ajax', 'CompanyClientController@GetCompanyClientData')->name('companyclient.data');
    Route::get('view-companyclient', 'CompanyClientController@CompanyClient')->name('companyclient.manage');
    Route::post('edit-companyclient', 'CompanyClientController@EditCompanyClient')->name('companyclient.edit');
    Route::get('delete-companyclient/{id}', 'CompanyClientController@DeleteCompanyClient')->name('companyclient.delete');




    Route::POST('add-new-sprtserv', 'SupportServiceController@AddNewSupportService')->name('sprtserv.register');
    Route::POST('edit-sprtserv-ajax', 'SupportServiceController@GetSupportServiceData')->name('sprtserv.data');
    Route::get('view-sprtserv', 'SupportServiceController@SupportService')->name('sprtserv.manage');
    Route::post('edit-sprtserv', 'SupportServiceController@EditSupportService')->name('sprtserv.edit');
    Route::get('delete-sprtserv/{id}', 'SupportServiceController@DeleteSupportService')->name('sprtserv.delete');


    Route::POST('add-new-sprtservtocomp', 'TagSupportServiceController@AddNewTagSupportService')->name('sprtservtocomp.register');
    Route::POST('edit-sprtservtocomp-ajax', 'TagSupportServiceController@GetTagSupportServiceData')->name('sprtservtocomp.data');
    Route::get('view-sprtservtocomp', 'TagSupportServiceController@TagSupportService')->name('sprtservtocomp.manage');
    Route::post('edit-sprtservtocomp', 'TagSupportServiceController@EditTagSupportService')->name('sprtservtocomp.edit');
    Route::get('delete-sprtservtocomp/{id}', 'TagSupportServiceController@DeleteTagSupportService')->name('sprtservtocomp.delete');



// subadmin

    Route::get('view-manager-availability', 'SubAdminController@SubAdminAvailability')->name('manager.manage-manager');
    Route::POST('add-new-subAdmin', 'SubAdminController@AddNewSubAdmin')->name('subadmin.subAdmin_register');
    Route::POST('edit-subAdmin-ajax', 'SubAdminController@GetSubAdminData')->name('subAdmin.data');

    Route::get('view-subAdmin', 'SubAdminController@SubAdminAvailability')->name('subAdmin.availability');

    Route::post('edit-subAdmin', 'SubAdminController@EditSubAdmin')->name('subAdmin.edit');
    Route::get('delete-subadmin/{id}', 'SubAdminController@DeleteSubAdmin')->name('subAdmin.delete');
    // subadmin

    Route::get('view-centerconfig', 'MeetingroomController@GetMeetingroomDatawithcenter')->name('meetingroom.managecenterconfig');
    Route::get('view-servicecategory', 'CentreController@getStatusTagCategory')->name('servicecategory.manage');

    Route::get('change-service-status/{id}', 'CentreController@ServiceChangeStatus')->name('center-service.changestatus');

    Route::post('edit-meetingroom', 'MeetingroomController@EditMeetingroommanager')->name('meetingroom.editmanager');

    Route::POST('add-new-companyoffer', 'CompanyOfferController@AddNewCompanyOffer')->name('companyoffer.register');
    Route::POST('edit-companyoffer-ajax', 'CompanyOfferController@GetCompanyOfferData')->name('companyoffer.data');
    Route::get('view-companyoffer', 'CompanyOfferController@CompanyOffers')->name('companyoffer.manage');
    Route::post('edit-companyoffer', 'CompanyOfferController@EditCompanyOffer')->name('companyoffer.edit');
    Route::get('delete-companyoffer/{id}', 'CompanyOfferController@DeleteCompanyOffer')->name('companyoffer.delete');

    Route::post('populate-configuration', 'MsinfoController@GetMsinfoDataByCat');

    Route::post('get-centreLocationLink-ajax', 'CentreController@GetCentreLocation');

    Route::POST('add-new-event', 'EventController@AddNewEvent')->name('event.register');
    Route::get('view-event', 'EventController@Events')->name('event.manage');
    Route::POST('edit-event-ajax', 'EventController@GetEventData')->name('event.data');

    Route::post('edit-event', 'EventController@EditEvent')->name('event.edit');
    Route::get('delete-event/{id}', 'EventController@DeleteEvent')->name('event.delete');


    Route::ANY('add-new-booking', 'BookingController@AddNewBooking')->name('booking.create');
    Route::ANY('add-new-multbooking/{id}', 'BookingController@AddNewBookingMult')->name('multbooking.create');

    Route::get('select-service/{id}', 'BookingController@SelectService')->name('booking.select');


    Route::get('view-query', 'QueryController@Queries')->name('query.manage');
    Route::get('delete-query/{id}', 'QueryController@DeleteQuery')->name('query.delete');

    Route::get('export-query', 'QueryController@exportToExcelQuery')->name('query.export');

    Route::get('view-quote', 'QueryController@Quotes')->name('quote.manage');
    Route::get('delete-quote/{id}', 'QueryController@DeleteQuote')->name('quote.delete');

    Route::get('export-quote', 'QueryController@exportToExcelQuote')->name('quote.export');

    Route::get('view-pquote', 'PQuoteController@PQuotes')->name('pquote.manage');
    Route::get('delete-pquote/{id}', 'PQuoteController@DeletePQuote')->name('pquote.delete');

    Route::get('export-pquote', 'PQuoteController@exportToExcelPQuote')->name('pquote.export');

    Route::get('view-rac', 'ReferClientController@ReferClients')->name('rac.manage');
    Route::get('delete-rac/{id}', 'ReferClientController@DeleteReferClient')->name('rac.delete');

    Route::get('export-rac', 'ReferClientController@exportToExcelReferClient')->name('rac.export');


    Route::ANY('uploadCorporateCust', 'CompanyController@UploadCorporateCust')->name('corporatecust.upload');

    Route::ANY('export-booking', 'BookingController@ExportBooking')->name('booking.export');
    Route::ANY('get-packageprice-ajax', 'BookingController@getPackagePrice');


    Route::POST('add-new-clientbenefits', 'ClientBenefitsController@AddNewClientBenefits')->name('clientbenefits.register');
    Route::get('view-clientbenefits', 'ClientBenefitsController@ClientBenefits')->name('clientbenefits.manage');
    Route::POST('edit-clientbenefits-ajax', 'ClientBenefitsController@GetClientBenefitsData')->name('clientbenefits.data');
    Route::post('edit-clientbenefits', 'ClientBenefitsController@EditClientBenefits')->name('clientbenefits.edit');
    Route::get('delete-clientbenefits/{id}', 'ClientBenefitsController@DeleteClientBenefits')->name('clientbenefits.delete');

    Route::get('update-comp', 'CustomerController@UpdateCompanyId');

    Route::get('manage-notifications', 'NotificationController@Notifications')->name('notification.manage');
    Route::POST('send-notifications', 'NotificationController@SendNotifications')->name('notification.send');
});
