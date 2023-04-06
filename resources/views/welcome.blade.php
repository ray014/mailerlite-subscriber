<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MailerLite Subscriber</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" >
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="antialiased">
        <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">MailerLite Subscriber</a>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

            </ul>
            <button class="btn btn-success" type="button" data-bs-toggle="modal" data-bs-target="#subscriberModal">Add Subscriber</button>
            </div>
        </div>
        </nav>
        <div class="px-5 mt-3">
            <div class="alert alert-success" role="alert" id="successAlertMessage" style='display:none'></div>
            <div class="alert alert-danger" role="alert" id="errorAlertMessage" style='display:none'></div>
        </div>
        <div class="px-4 py-3 mt-2 mb-2 text-center">
            <table id="subscriberTable" class="display" style='display: none;'>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Subscribe Date</th>
                        <th>Subscribe Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </body>

    <div class="modal fade" id="subscriberModal" tabindex="-1" aria-labelledby="subscriberModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content rounded-4 shadow">
              <div class="modal-header p-5 pb-4 border-bottom-0">
                <h1 class="fw-bold mb-0 fs-5" id="subscriberModalTitle"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
        
              <div class="modal-body p-5 pt-0">
                <form>
                  <div class="alert alert-danger" role="alert" id="errorMessage" style='display:none'></div>
                  <div class="loader-container" id="loaderContainer" style='display:none;'><span class="loader-black"></span></div>
                  <div class="form-floating mb-3">
                    <input type="email" class="form-control rounded-3" id="floatingEmail" placeholder="name@example.com" required>
                    <label for="floatingEmail">Email address</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="floatingName" placeholder="Name" required>
                    <label for="floatingName">Name</label>
                  </div>
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control rounded-3" id="floatingCountry" placeholder="Country" required>
                    <label for="floatingCountry">Country</label>
                  </div>
                  <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" id="btnSubscribe" type="submit">Submit</button>
                </form>
              </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="apiKeyModal" tabindex="-1" aria-labelledby="apiKeyModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
              <h1 class="fw-bold mb-0 fs-5">Insert a valid MailerLite API Key</h1>
            </div>
      
            <div class="modal-body p-5 pt-0">
              <form>
                <div class="alert alert-danger" role="alert" id="errorMessageApiKey" style='display:none'>Error MailerLite API key is not valid, please use a valid key.</div>
                <div class="alert alert-success" role="alert" id="successMessageApiKey" style='display:none'>MailerLite API Key is valid! Redirecting to subscriber page...</div>
                <div class="form-floating mb-3">
                  <input type="text" class="form-control rounded-3" id="floatingApiKey" placeholder="Api Key" required>
                  <label for="floatingApiKey">Api Key</label>
                </div>
                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" id="btnSubmitApiKey" type="submit">Submit</button>
              </form>
            </div>
          </div>
      </div>
  </div>

  <input type="hidden" id="apiKeyExist" value="{{ $apiKeyExist }}">
</html>

<script>
    const subscriber = {
        table: false,
        subscriberModal: false,
        modalTitle: false,
        btnSubscribe: false,
        successAlertMessage: false,
        errorAlertMessage: false,
        errorMessage: false,
        emailInput: false,
        nameInput: false,
        countryInput: false,
        loaderContainer: false,
        editSubscriberData: {},

        apiKeyModal: false,
        apiKeyExist: false,
        successMessageApiKey: false,
        errorMessageApiKey: false,
        floatingApiKey: false,
        btnSubmitApiKey: false,


        /**
         * Initialize variables and events
         */
        init: function() {
            subscriber.apiKeyExist = $('#apiKeyExist');

            // Show modal to enter mailer lite api key if key doesn't exist
            if (!subscriber.apiKeyExist.val()) {
                subscriber.apiKeyModal = $('#apiKeyModal');
                subscriber.successMessageApiKey = $('#successMessageApiKey');
                subscriber.errorMessageApiKey = $('#errorMessageApiKey');
                subscriber.floatingApiKey = $('#floatingApiKey');
                subscriber.btnSubmitApiKey = $('#btnSubmitApiKey');

                subscriber.initApiKeyModal();
                return;
            }

            subscriber.table = $('#subscriberTable');
            subscriber.subscriberModal = $('#subscriberModal');
            subscriber.modalTitle = $('#subscriberModalTitle');
            subscriber.btnSubscribe = $('#btnSubscribe');
            subscriber.successAlertMessage = $('#successAlertMessage');
            subscriber.errorAlertMessage = $('#errorAlertMessage');
            subscriber.errorMessage = $('#errorMessage');
            subscriber.emailInput = $('#floatingEmail');
            subscriber.nameInput = $('#floatingName');
            subscriber.countryInput = $('#floatingCountry');
            subscriber.loaderContainer = $('#loaderContainer');

            subscriber.initDataTable();
            subscriber.initSubscriberModal();
            subscriber.btnSubscribe.click(subscriber.handleSubscribeSubmission);
            subscriber.table.show();
        },

        /**
         * Initialize api key modal to enter mailer lite api key
         */
        initApiKeyModal: function() {
            subscriber.apiKeyModal.modal({backdrop: 'static', keyboard: false});
            subscriber.apiKeyModal.modal('show');

            subscriber.btnSubmitApiKey.click(function(e) {
                let key = subscriber.floatingApiKey.val();
                if (!key) {
                    return;
                }

                subscriber.errorMessageApiKey.hide();

                subscriber.btnSubmitApiKey.html('<span class="loader"></span>')
                subscriber.btnSubmitApiKey.prop('disabled', true);
                e.preventDefault();

                subscriber.submitApiKey(key)
            })
        },

        /**
         * Submit api key data
         */
         submitApiKey: function(key) {
            $.ajax({
                type: "POST",
                url: "/api/api-key/",
                data: {
                  'key': key
                },
                success: function(result) {
                    subscriber.successMessageApiKey.show();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                },
                error: function(result) {
                    subscriber.btnSubmitApiKey.html('Submit')
                    subscriber.btnSubmitApiKey.prop('disabled', false);
                    subscriber.errorMessageApiKey.show();
                }
            });
        },

        /**
         * Initialize DataTable using server side processing
         */
        initDataTable: function() {
            subscriber.table.DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searchDelay: 1000,
                ajax: '/api/subscriber',
                  "columns": [
                      {
                          "data": "email",
                          render: function(data, type, row)
                          {
                            return '<span class="edit-row" onclick="subscriber.openEditSubscriberModal(\'' + row.id + '\', \'' + row.email + '\', \'' + row.name + '\', \'' + row.country + '\')">' + data + '</span>';
                          }
                      },
                      { "data": "name" },
                      { "data": "country" },
                      { "data": "subscribe_date" },
                      { "data": "subscribe_time" },
                      {
                          "data": "id",
                          render: function(data, type, row)
                          {
                            return '<button type="button" class="btn btn-danger" onclick="subscriber.deleteSubscriber(this, \'' + row.id + '\', \'' + row.email + '\')">Delete</button>';
                          }
                      }
                  ]
            });
        },

        /**
         * Initialize subscriber modal
         */ 
        initSubscriberModal: function() {
            subscriber.subscriberModal.on('show.bs.modal', function (event) {
                subscriber.emailInput.val('');
                subscriber.nameInput.val('');
                subscriber.countryInput.val('');
                subscriber.successAlertMessage.hide();
                subscriber.errorMessage.hide();
                subscriber.btnSubscribe.prop('disabled', false);

                if (!$.isEmptyObject(subscriber.editSubscriberData)) {
                    subscriber.loaderContainer.show();
                    let fetchData = subscriber.fetchSubscription(subscriber.editSubscriberData.id);

                    subscriber.emailInput.prop('required', false);
                    subscriber.emailInput.parent().hide();
                    subscriber.nameInput.parent().hide();
                    subscriber.countryInput.parent().hide();
                    subscriber.btnSubscribe.prop('disabled', true);

                    subscriber.modalTitle.html('Update subscription ' + subscriber.editSubscriberData.email);
                    subscriber.btnSubscribe.html('Update');
                    return;
                }

                subscriber.emailInput.prop('required', true);
                subscriber.emailInput.parent().show();
                subscriber.nameInput.parent().show();
                subscriber.countryInput.parent().show();
                subscriber.btnSubscribe.html('Submit');
                subscriber.modalTitle.html('Add Subscriber');
            });

            subscriber.subscriberModal.on('hide.bs.modal', function (event) {
                subscriber.editSubscriberData = {};
            });
        },

        /**
         * Open modal to update subscriber data
         */
        openEditSubscriberModal: function(id, email, name, country) {
            subscriber.editSubscriberData = {
                'id': id,
                'email': email,
                'name': name,
                'country': country
            };
            subscriber.subscriberModal.modal('show');
        },

        /**
         * Handle submit button click event on create/update subscriber data
         */
        handleSubscribeSubmission: function(e) {
            subscriber.successAlertMessage.hide();
            subscriber.errorMessage.hide();

            let values = {
                email: subscriber.emailInput.val(),
                name: subscriber.nameInput.val(),
                country: subscriber.countryInput.val(),
            };

            if (($.isEmptyObject(subscriber.editSubscriberData) && !values.email) || !values.name || !values.country) {
                return;
            }

            subscriber.btnSubscribe.html('<span class="loader"></span>')
            subscriber.btnSubscribe.prop('disabled', true);
            e.preventDefault();

            if (!$.isEmptyObject(subscriber.editSubscriberData)) {
                subscriber.updateSubscription(values);
                subscriber.editSubscriberData = {};
                return;
            }

            subscriber.addSubscription(values);
        },

        /**
         * Fetch subscriber data to populate in edit form
         */
         fetchSubscription: function(id) {
            $.ajax({
                type: "GET",
                url: "/api/subscriber/" + id,
                success: function(result) {
                    subscriber.nameInput.val(result.name ? result.name : '');
                    subscriber.countryInput.val(result.country ? result.country : '');
                    subscriber.loaderContainer.hide();
                    subscriber.nameInput.parent().show();
                    subscriber.countryInput.parent().show();
                    subscriber.btnSubscribe.prop('disabled', false);
                },
                error: function(result) {
                    subscriber.subscriberModal.modal('hide');
                    subscriber.errorMessage.html('Error subscriber not found, please try again');
                    subscriber.table.dataTable().fnDraw();
                    subscriber.loaderContainer.hide();
                    subscriber.nameInput.parent().show();
                    subscriber.countryInput.parent().show();
                    subscriber.btnSubscribe.prop('disabled', false);
                }
            });
        },

        /**
         * Add subscriber data
         */
        addSubscription: function(values) {
            $.ajax({
                type: "POST",
                url: "/api/subscriber/",
                data: values,
                success: function(result) {
                    subscriber.showSuccessAlertMessage('Subscriber added successfully');
                    subscriber.subscriberModal.modal('hide');
                    subscriber.table.dataTable().fnDraw();
                },
                error: function(result) {
                    subscriber.errorMessage.html(result.responseJSON.message);
                    subscriber.errorMessage.show();
                    subscriber.btnSubscribe.html('Submit');
                    subscriber.btnSubscribe.prop('disabled', false);
                }
            });
        },

        /**
         * Update subscriber data
         */
         updateSubscription: function(values) {
            let id = subscriber.editSubscriberData.id,
                email = subscriber.editSubscriberData.email;

            $.ajax({
                type: "PUT",
                url: "/api/subscriber/" + id,
                data: { 
                    name: values.name,
                    country: values.country
                },
                success: function(result) {
                    subscriber.showSuccessAlertMessage('Subscriber <b>' + email + '</b> updated successfully');
                    subscriber.subscriberModal.modal('hide');
                    subscriber.table.dataTable().fnDraw();
                },
                error: function(result) {
                    subscriber.errorMessage.html(result.responseJSON.message);
                    subscriber.errorMessage.show();
                    subscriber.btnSubscribe.html('Subscribe');
                    subscriber.btnSubscribe.prop('disabled', false);
                }
            });
        },

        /**
         * Delete subscriber data
         */
        deleteSubscriber: function(el, id, email) {
            $(el).html('<span class="loader"></span>');
            $(el).prop('disabled', true);
            $.ajax({
                type: "DELETE",
                url: "/api/subscriber/" + id,
                success: function(result) {
                    subscriber.table.dataTable().fnDraw();
                    subscriber.showSuccessAlertMessage('Subscriber <b>' + email + '</b> deleted successfully');
                },
                error: function(result) {
                  $(el).html('Delete');
                  $(el).prop('disabled', false);
                  subscriber.showErrorAlertMessage('An unknown error occured, please try again');
                }
            });
        },

        /**
         * Show success alert message above data table
         */
        showSuccessAlertMessage: function(message) {
            subscriber.successAlertMessage.html(message);
            subscriber.successAlertMessage.show();
            setTimeout(() => {
                subscriber.successAlertMessage.hide();
            }, 5000);
        },

        /**
         * Show error alert message above data table
         */
        showErrorAlertMessage: function(message) {
            subscriber.errorAlertMessage.html(message);
            subscriber.errorAlertMessage.show();
            setTimeout(() => {
                subscriber.errorAlertMessage.hide();
            }, 5000);
        },
    };

    window.addEventListener('load', subscriber.init);
</script>