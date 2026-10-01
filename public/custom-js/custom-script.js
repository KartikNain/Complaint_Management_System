$(document).ready(function () {

    $(document).on('click', '#appSidebar .nav-link', function (e) {

        const link = $(this);
        const url = link.data('url');

        // If this is a normal link without AJAX data-url
        if (!url) {
            return;
        }

        e.preventDefault();

        // Remove active from every sidebar menu
        $('#appSidebar .nav-link').removeClass('active');

        // Add active only to clicked menu
        link.addClass('active');

        // Load your AJAX page
        loadPage(url, link.data('title'));
    });


    // Send CSRF token with every AJAX request automatically
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const swalTheme = {
        popup: 'custom-swal',
        title: 'custom-swal-title',
        htmlContainer: 'custom-swal-text',
        confirmButton: 'custom-swal-confirm',
        cancelButton: 'custom-swal-cancel'
    };

    /* ======================================================
       Register form
       ====================================================== */
    $('#registerForm').submit(function (e) {

        e.preventDefault();

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        const registerStoreUrl = $('#registerForm').data('url');

        $.ajax({
            url: registerStoreUrl,
            type: 'POST',
            data: $(this).serialize(),

            success: function (response) {

                $('#registerForm')[0].reset();

                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, go to dashboard',
                    cancelButtonText: 'No, stay here',
                    reverseButtons: true,
                    customClass: swalTheme,
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'employee/dashboard';
                    }
                });

            },

            error: function (xhr) {

                if (xhr.status == 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $('[name="' + key + '"]').addClass('is-invalid');
                        $('[name="' + key + '"]').after(
                            '<div class="invalid-feedback">' + value[0] + '</div>'
                        );
                    });

                } else {

                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: swalTheme,
                        buttonsStyling: false
                    });

                }

            }
        });

    });

    /* ======================================================
       Login form
       ====================================================== */
    $('#loginForm').submit(function (e) {

        e.preventDefault();

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');

        const loginUrl = $('#loginForm').data('url');

        $.ajax({
            url: loginUrl,
            type: 'POST',
            data: $(this).serialize(),

            success: function (response) {

                $('#loginForm')[0].reset();

                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: swalTheme
                }).then(() => {
                    window.location.href = response.redirect;
                });

            },

            error: function (xhr) {

                if (xhr.status == 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $('[name="' + key + '"]').addClass('is-invalid');
                        $('[name="' + key + '"]').after(
                            '<div class="invalid-feedback">' + value[0] + '</div>'
                        );
                    });

                } else if (xhr.status == 401) {

                    Swal.fire({
                        title: 'Login Failed',
                        text: xhr.responseJSON.message,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: swalTheme,
                        buttonsStyling: false
                    });

                }

            }
        });

    });

    /* ======================================================
       Logout
       ====================================================== */
    $('#logoutBtn').on('click', function () {

        Swal.fire({
            title: 'Logout?',
            text: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Logout',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: swalTheme,
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $('#logoutForm').submit();
            }
        });

    });

    /* ======================================================
       Responsive sidebar (mobile off-canvas)
       ====================================================== */
    const $sidebar = $('#appSidebar');
    const $backdrop = $('#sidebarBackdrop');

    function openSidebar() {
        $sidebar.addClass('show');
        $backdrop.addClass('show');
    }

    function closeSidebar() {
        $sidebar.removeClass('show');
        $backdrop.removeClass('show');
    }

    $('#sidebarToggleBtn').on('click', openSidebar);
    $('#sidebarCloseBtn').on('click', closeSidebar);
    $backdrop.on('click', closeSidebar);

    // Close the mobile sidebar automatically if resized back to desktop
    $(window).on('resize', function () {
        if ($(window).width() >= 992) {
            closeSidebar();
        }
    });

    /* ======================================================
       Profile modal — avatar preview
       ====================================================== */
    $('#avatarInput').on('change', function () {

        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            $('#avatarPreview').html('<img src="' + e.target.result + '" alt="Profile photo">');
            $('#headerAvatar').html('<img src="' + e.target.result + '" alt="Profile photo">');
        };
        reader.readAsDataURL(file);

    });

    /* ======================================================
       Profile modal — update info (static/demo for now)
       ====================================================== */
    $('#profileInfoForm').submit(function (e) {

        e.preventDefault();

        $(this).find('.invalid-feedback').remove();
        $(this).find('.is-invalid').removeClass('is-invalid');

        const url = $(this).data('url');
        const formData = new FormData(this);
        const $submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = $submitBtn.html();

        $submitBtn.prop('disabled', true).text('Saving...');

        $.ajax({

            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                // Update the photo shown in the modal and in the header
                if (response.profile_image_url) {
                    $('#avatarPreview').html('<img src="' + response.profile_image_url + '" alt="Profile photo">');
                    $('#headerAvatar').html('<img src="' + response.profile_image_url + '" alt="Profile photo">');
                }

                if (response.name) {
                    $('.header-user-name').text(response.name);
                }

                Swal.fire({
                    title: 'Profile Updated',
                    text: response.message,
                    icon: 'success',
                    timer: 1600,
                    showConfirmButton: false,
                    customClass: swalTheme
                });

                bootstrap.Modal.getInstance(document.getElementById('profileModal'))?.hide();

            },

            error: function (xhr) {

                if (xhr.status === 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $('#profileInfoForm [name="' + key + '"]').addClass('is-invalid');
                        $('#profileInfoForm [name="' + key + '"]').after(
                            '<div class="invalid-feedback">' + value[0] + '</div>'
                        );
                    });

                } else {

                    Swal.fire({
                        title: 'Error',
                        text: 'Could not update your profile. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: swalTheme,
                        buttonsStyling: false
                    });

                }

            },

            complete: function () {
                $submitBtn.prop('disabled', false).html(originalBtnText);
            }

        });

    });

    /* ======================================================
       Profile modal — change password (static/demo for now)
       ====================================================== */
    $(document).on('submit', '#profilePasswordForm', function (e) {

        e.preventDefault();

        let form = $(this);
        let url = form.data('url');
        let submitButton = form.find('button[type="submit"]');

        // Remove previous validation errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').remove();

        let originalBtnText = submitButton.html();

        submitButton
            .prop('disabled', true)
            .html('Updating...');

        $.ajax({

            url: url,
            type: 'POST',
            data: form.serialize(),

            success: function (response) {

                Swal.fire({
                    title: 'Password Updated',
                    text: response.message,
                    icon: 'success',
                    timer: 1600,
                    showConfirmButton: false,
                    customClass: swalTheme
                });

                // Reset password form
                form[0].reset();

            },

            error: function (xhr) {

                if (xhr.status === 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {

                        let input = form.find('[name="' + key + '"]');

                        input.addClass('is-invalid');

                        input.after(
                            '<div class="invalid-feedback">' +
                            value[0] +
                            '</div>'
                        );

                    });

                } else {

                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON?.message ||
                            'Could not update your password. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: swalTheme,
                        buttonsStyling: false
                    });

                }

            },

            complete: function () {

                submitButton
                    .prop('disabled', false)
                    .html(originalBtnText);

            }

        });

    });


    /* ======================================================
   Add User Form
   ====================================================== */

    $('#addUserForm').submit(function (e) {

        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        // Remove previous validation errors
        form.find('.invalid-feedback').remove();
        form.find('.is-invalid').removeClass('is-invalid');

        // Disable button
        submitBtn
            .prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Creating...');

        const storeUserUrl = form.data('url');

        $.ajax({

            url: storeUserUrl,

            type: 'POST',

            data: form.serialize(),

            success: function (response) {

                // Reset form
                form[0].reset();

                Swal.fire({
                    title: 'User Created',
                    text: response.message,
                    icon: 'success',
                    timer: 1600,
                    showConfirmButton: false,
                    customClass: swalTheme
                });

                // Close modal after successful creation
                setTimeout(function () {

                    const modalElement = document.getElementById('usersModal');

                    const modalInstance =
                        bootstrap.Modal.getInstance(modalElement);

                    if (modalInstance) {
                        modalInstance.hide();
                    }

                }, 1600);

            },

            error: function (xhr) {

                if (xhr.status === 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {

                        const input = form.find('[name="' + key + '"]');

                        input.addClass('is-invalid');

                        input.after(
                            '<div class="invalid-feedback">' +
                            value[0] +
                            '</div>'
                        );

                    });

                } else {

                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON?.message ||
                            'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: swalTheme,
                        buttonsStyling: false
                    });

                }

            },

            complete: function () {

                // Enable button again
                submitBtn
                    .prop('disabled', false)
                    .html(originalBtnText);

            }

        });

    });




    function loadPage(url, title) {

        const $content = $('#appContent');

        $content.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <div class="mt-2">Loading...</div>
            </div>
        `);

        $.ajax({
            url: url,
            type: 'GET',

            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },

            success: function (response) {

                console.log('AJAX Response:', response);

                $content.html(response);

                $('#pageTitle').text(title);
                document.title = title + ' - CMS';

                // $('.nav-link').removeClass('active');
                // $('#submitComplaintLink a').addClass('active');

                history.pushState({ ajaxNav: true, url: url, title: title }, '', url);
                closeSidebar();
            },

            error: function (xhr) {

                console.log('AJAX Error:', xhr);

                $content.html(`
                    <div class="alert alert-danger m-4">
                        error in page reload, please try again.
                    </div>
                `);
            }
        });
    }

    // Sidebar link click
    $(document).on('click', '#submitComplaintLink a', function (e) {

        e.preventDefault();

        const url = $(this).data('url');
        const title = $(this).data('title');

        loadPage(url, title);
    });


    $(document).on('submit', '#submitComplaintForm', function (e) {

        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalBtnText = submitBtn.html();

        // Remove previous validation errors
        form.find('.invalid-feedback').remove();
        form.find('.is-invalid').removeClass('is-invalid');

        // Disable button
        submitBtn
            .prop('disabled', true)
            .html(`
                <span class="spinner-border spinner-border-sm me-1"></span>
                Submitting...
            `);

        const storeComplaintUrl = form.data('url');

        // IMPORTANT: FormData is required because of file upload
        const formData = new FormData(form[0]);

        $.ajax({

            url: storeComplaintUrl,

            type: 'POST',

            data: formData,

            processData: false,

            contentType: false,

            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },

            success: function (response) {

                // Reset form
                form[0].reset();

                Swal.fire({
                    title: 'Complaint Submitted',
                    text: response.message,
                    icon: 'success',
                    timer: 1800,
                    showConfirmButton: false,
                    customClass: swalTheme
                });

            },

            error: function (xhr) {

                if (xhr.status === 422) {

                    $.each(xhr.responseJSON.errors, function (key, value) {

                        const input = form.find('[name="' + key + '"]');

                        input.addClass('is-invalid');

                        input.after(
                            '<div class="invalid-feedback">' +
                            value[0] +
                            '</div>'
                        );

                    });

                } else {

                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON?.message ||
                            'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        customClass: swalTheme,
                        buttonsStyling: false
                    });

                }

            },

            complete: function () {

                // Enable button again
                submitBtn
                    .prop('disabled', false)
                    .html(originalBtnText);

            }

        });

    });



    // Complaint list -> view detail (reuses loadPage)
    $(document).on('click', '.complaint-view-link, .complaint-back-link', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
        const title = $(this).data('title');
        loadPage(url, title);
    });

    // Filter form (GET, re-render list via AJAX)
    // $(document).on('submit', '#complaintFilterForm', function (e) {
    //     e.preventDefault();
    //     const baseUrl = $(this).data('url');
    //     const params = $(this).serialize();
    //     loadPage(baseUrl + '?' + params, 'Manage Complaints');
    // });



    // Show/hide resolution summary box based on status
    $(document).on('change', '#statusSelect', function () {
        if ($(this).val() === 'Resolved') {
            $('#resolutionSummaryWrap').slideDown(150);
        } else {
            $('#resolutionSummaryWrap').slideUp(150);
        }
    });

    // Assign complaint
    $(document).on('submit', '#assignComplaintForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const url = form.data('url');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                Swal.fire({
                    title: 'Assigned',
                    text: response.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: swalTheme
                }).then(() => {
                    loadPage(window.location.pathname, 'Complaint Detail');
                });
            },
            error: function (xhr) {
                Swal.fire({
                    title: 'Error',
                    text: xhr.responseJSON?.message || 'Something went wrong.',
                    icon: 'error',
                    customClass: swalTheme,
                    buttonsStyling: false
                });
            }
        });
    });

    // Update status
    $(document).on('submit', '#updateStatusForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const url = form.data('url');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                Swal.fire({
                    title: 'Status Updated',
                    text: response.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: swalTheme
                }).then(() => {
                    loadPage(window.location.pathname, 'Complaint Detail');
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    Swal.fire({
                        title: 'Validation Error',
                        text: xhr.responseJSON.errors.resolution_summary?.[0] || 'Please check the form.',
                        icon: 'warning',
                        customClass: swalTheme,
                        buttonsStyling: false
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong.',
                        icon: 'error',
                        customClass: swalTheme,
                        buttonsStyling: false
                    });
                }
            }
        });
    });


    $(document).on(
        'click',
        '#complaintsTableContainer .pagination a',
        function (e) {

            e.preventDefault();

            const url = $(this).attr('href');

            $.ajax({

                url: url,

                type: 'GET',

                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },

                beforeSend: function () {

                    $('#complaintsTableContainer')
                        .css('opacity', '0.5');

                },

                success: function (response) {

                    $('#complaintsTableContainer')
                        .html(response);

                    window.history.pushState(
                        {},
                        '',
                        url
                    );

                },

                error: function (xhr) {

                    console.error(xhr.responseText);

                },

                complete: function () {

                    $('#complaintsTableContainer')
                        .css('opacity', '1');

                }

            });

        }
    );



    /**
     * Staff Complaints - AJAX handlers
     * Uses event delegation on document so it keeps working after
     * #appContent is swapped by the SPA nav loader.
     */
    (function () {

        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.content : '';
        }

        function toast(message, isError = false) {
            // Replace this with your existing toast/notification system if you have one
            if (isError) {
                console.error(message);
                alert(message);
            } else {
                console.log(message);
            }
        }

        // Toggle resolution_summary textarea when status = Resolved
        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'staffStatusSelect') {
                const wrap = document.getElementById('staffResolutionSummaryWrap');
                if (!wrap) return;
                wrap.style.display = e.target.value === 'Resolved' ? '' : 'none';
            }
        });

        // Update Status form submit
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.id !== 'staffUpdateStatusForm') return;

            e.preventDefault();

            const url = form.dataset.url;
            const formData = new FormData(form);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        toast(data.message);

                        const badge = document.getElementById('statusBadge');
                        if (badge) {
                            badge.textContent = data.new_status;
                            badge.className = 'badge bg-' + (
                                data.new_status === 'In Progress' ? 'primary' :
                                    data.new_status === 'Resolved' ? 'success' :
                                        data.new_status === 'Reopened' ? 'danger' : 'info'
                            );
                        }

                        if (data.new_status === 'Resolved') {
                            // hide the update form section (complaint is now closed for editing here)
                            form.closest('.border.rounded').style.display = 'none';
                        }
                    } else {
                        toast(data.message || 'Something went wrong.', true);
                    }
                })
                .catch(() => toast('Something went wrong. Please try again.', true));
        });

        // Add Comment form submit
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.id !== 'staffAddCommentForm') return;

            e.preventDefault();

            const url = form.dataset.url;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            if (submitBtn) submitBtn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        const list = document.getElementById('conversationList');
                        const emptyMsg = document.getElementById('noConversationMsg');
                        if (emptyMsg) emptyMsg.remove();

                        if (list) {
                            list.insertAdjacentHTML('beforeend', data.html);
                            list.scrollTop = list.scrollHeight;
                        }

                        form.reset();
                    } else {
                        toast(data.message || 'Could not send comment.', true);
                    }
                })
                .catch(() => toast('Could not send comment. Please try again.', true))
                .finally(() => {
                    if (submitBtn) submitBtn.disabled = false;
                });
        });

        // Filter form (status/priority/search) - reuse your existing generic AJAX
        // filter handler if you already have one for the admin panel (e.g. one bound
        // to any form with a data-url that targets a *TableContainer div).
        // If you don't have a generic one yet, wire #staffComplaintFilterForm the
        // same way you wired #complaintFilterForm on the admin side.

        // Add Comment form submit (Employee side)
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (form.id !== 'employeeAddCommentForm') return;

            e.preventDefault();

            const url = form.dataset.url;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');

            if (submitBtn) submitBtn.disabled = true;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: formData,
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        const list = document.getElementById('conversationList');
                        const emptyMsg = document.getElementById('noConversationMsg');
                        if (emptyMsg) emptyMsg.remove();

                        if (list) {
                            list.insertAdjacentHTML('beforeend', data.html);
                            list.scrollTop = list.scrollHeight;
                        }

                        form.reset();
                    } else {
                        toast(data.message || 'Could not send comment.', true);
                    }
                })
                .catch(() => toast('Could not send comment. Please try again.', true))
                .finally(() => {
                    if (submitBtn) submitBtn.disabled = false;
                });
        });

        // Accept / Reopen resolution buttons (Employee side)
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.employee-decision-btn');
            if (!btn) return;

            const url = btn.dataset.url;
            const decision = btn.dataset.decision;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ decision: decision }),
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status) {
                        toast(data.message);

                        const badge = document.getElementById('statusBadge');
                        if (badge) {
                            badge.textContent = data.new_status;
                            badge.className = 'badge bg-' + (
                                data.new_status === 'Closed' ? 'secondary' :
                                    data.new_status === 'Reopened' ? 'danger' : 'info'
                            );
                        }

                        const box = document.getElementById('resolutionDecisionBox');
                        if (box) box.querySelector('.d-flex.gap-2')?.remove();
                    } else {
                        toast(data.message || 'Something went wrong.', true);
                    }
                })
                .catch(() => toast('Something went wrong. Please try again.', true));
        });


    })();



    let searchTimer;

    function filterComplaints(form, focusSearch = true) {

        $.ajax({
            url: form.data('url'),
            type: 'GET',
            data: form.serialize(),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },

            success: function (response) {

                const panel = form.closest('.panel');

                panel.replaceWith(response);

                // Restore focus only after search
                if (focusSearch) {

                    const newPanel = $(response);

                    const search = newPanel.find('input[name="search"]');

                    if (search.length) {
                        search.focus();

                        const value = search.val() || '';

                        search[0].setSelectionRange(
                            value.length,
                            value.length
                        );
                    }
                }
            },

            error: function (xhr) {
                console.error('Filter error:', xhr.responseText);
            }
        });
    }


    /* =========================
       SEARCH
       ========================= */

    $(document).on(
        'keyup',
        '#complaintFilterForm input[name="search"], \
     #employeeComplaintFilterForm input[name="search"], \
     #staffComplaintFilterForm input[name="search"]',
        function () {

            const form = $(this).closest('form');

            clearTimeout(searchTimer);

            searchTimer = setTimeout(function () {
                filterComplaints(form, true);
            }, 200);
        }
    );


    /* =========================
       SELECT FILTERS
       ========================= */

    $(document).on(
        'change',
        '#complaintFilterForm select, \
     #employeeComplaintFilterForm select, \
     #staffComplaintFilterForm select',
        function () {

            filterComplaints(
                $(this).closest('form'),
                false
            );

        }
    );


    /* =========================
       RESET
       ========================= */

    $(document).on(
        'click',
        '.reset-complaint-filters',
        function (e) {

            e.preventDefault();

            const form = $(this).closest('.panel').find('form');

            if (!form.length) {
                console.error('Complaint filter form not found');
                return;
            }

            // Clear all fields
            form.find('input').val('');
            form.find('select').val('');

            // Send empty filters
            filterComplaints(form, false);
        }
    );


    /* ======================================================
       Users — Add/Edit modal toggle + Delete
       ====================================================== */
    // $(document).on('click', '#addUserBtn', function () {
    //     const form = $('#addUserForm');
    //     form[0].reset();
    //     form.data('mode', 'create');
    //     form.data('url', '{{ route("admin.users.store") }}'); // Blade won't parse in .js file — see note below
    //     form.find('input[name="_method"]').remove();
    //     $('#usersModalTitle').text('Add User');
    //     $('#passwordHint').text('(min 6 characters)');
    //     form.find('[name="password"], [name="password_confirmation"]').prop('required', true);
    // });

    // $(document).on('click', '.user-edit-btn', function () {
    //     const btn = $(this);
    //     const form = $('#addUserForm');
    //     form[0].reset();

    //     form.find('[name="name"]').val(btn.data('name'));
    //     form.find('[name="email"]').val(btn.data('email'));
    //     form.find('[name="role"]').val(btn.data('role'));

    //     form.data('mode', 'edit');
    //     form.data('url', btn.data('url'));
    //     form.find('input[name="_method"]').remove();
    //     form.append('<input type="hidden" name="_method" value="PUT">');

    //     // Password optional on edit
    //     form.find('[name="password"], [name="password_confirmation"]').prop('required', false);

    //     $('#usersModalTitle').text('Edit User');
    //     $('#passwordHint').text('(leave blank to keep current password)');

    //     new bootstrap.Modal(document.getElementById('usersModal')).show();
    // });

    /* ======================================================
   Users — Add / Edit modal wiring
   ====================================================== */

    // Reset modal to "create" mode
    $(document).on('click', '#addUserBtn', function () {
        const btn = $(this);
        const form = $('#addUserForm');

        form[0].reset();
        form.find('input[name="_method"]').remove();
        form.data('mode', 'create');
        form.data('url', btn.data('url')); // store route, read from the button's data-url

        $('#usersModalTitle').text('Add User');
        $('#passwordHint').text('(min 6 characters)');
        form.find('[name="password"], [name="password_confirmation"]').prop('required', true);
    });

    // Populate modal for editing
    $(document).on('click', '.user-edit-btn', function () {
        const btn = $(this);
        const form = $('#addUserForm');

        form[0].reset();
        form.find('input[name="_method"]').remove();

        form.find('[name="name"]').val(btn.data('name'));
        form.find('[name="email"]').val(btn.data('email'));
        form.find('[name="role"]').val(btn.data('role'));

        form.data('mode', 'edit');
        form.data('url', btn.data('url'));        // ← this is the admin.users.update URL for THIS row
        form.append('<input type="hidden" name="_method" value="PUT">');

        form.find('[name="password"], [name="password_confirmation"]').prop('required', false);

        $('#usersModalTitle').text('Edit User');
        $('#passwordHint').text('(leave blank to keep current password)');

        new bootstrap.Modal(document.getElementById('usersModal')).show();
    });

    // Single submit handler for BOTH create and edit
    $(document).on('submit', '#addUserForm', function (e) {
        e.preventDefault();

        const form = $(this);
        const url = form.data('url');   // ← the key fix: always read the CURRENT data-url at submit time

        $.ajax({
            url: url,
            type: 'POST',                 // Laravel handles PUT via _method spoofing over POST
            data: form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                bootstrap.Modal.getInstance(document.getElementById('usersModal')).hide();

                Swal.fire({
                    title: 'Success',
                    text: response.message,
                    icon: 'success',
                    timer: 1200,
                    showConfirmButton: false
                });

                // ✅ this is almost certainly the missing piece — refresh the table without reload
                filterUsers($('#userFilterForm'));
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let msg = Object.values(errors).map(e => e[0]).join('<br>');
                    Swal.fire({ title: 'Validation Error', html: msg, icon: 'error' });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON?.message || 'Something went wrong.',
                        icon: 'error'
                    });
                }
            }
        });
    });


    $(document).on('click', '.user-delete-btn', function () {
        const url = $(this).data('url');

        Swal.fire({
            title: 'Delete User?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: swalTheme,
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { _method: 'DELETE' },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function (response) {
                        Swal.fire({ title: 'Deleted', text: response.message, icon: 'success', timer: 1200, showConfirmButton: false, customClass: swalTheme });
                        filterUsers($('#userFilterForm'));
                    },
                    error: function (xhr) {
                        Swal.fire({ title: 'Error', text: xhr.responseJSON?.message || 'Could not delete user.', icon: 'error', customClass: swalTheme, buttonsStyling: false });
                    }
                });
            }
        });
    });


    let userSearchTimer;
    let userFilterXhr = null;

    function filterUsers(form, focusSearch = true) {
        if (userFilterXhr) {
            userFilterXhr.abort();
        }

        userFilterXhr = $.ajax({
            url: form.data('url'),
            type: 'GET',
            data: form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },

            success: function (response) {
                const panel = form.closest('.panel');
                panel.replaceWith(response);

                if (focusSearch) {
                    const newPanel = $(response);
                    const search = newPanel.find('input[name="search"]');
                    if (search.length) {
                        search.focus();
                        const value = search.val() || '';
                        search[0].setSelectionRange(value.length, value.length);
                    }
                }
            },

            error: function (xhr, status) {
                if (status === 'abort') return; // expected — a newer request superseded this one
                console.error('Filter error:', xhr.responseText);
            }
        });
    }

    // Live search
    $(document).on('keyup', '#userFilterForm input[name="search"]', function () {
        const form = $(this).closest('form');
        clearTimeout(userSearchTimer);
        userSearchTimer = setTimeout(function () {
            filterUsers(form, true);
        }, 200);
    });

    // Role dropdown — instant, no focus restore needed
    $(document).on('change', '#userFilterForm select[name="role"]', function () {
        filterUsers($(this).closest('form'), false);
    });

    // Prevent native submit on Enter
    $(document).on('submit', '#userFilterForm', function (e) {
        e.preventDefault();
        clearTimeout(userSearchTimer);
        filterUsers($(this), true);
    });


    $(document).on('click', '#usersTableContainer .pagination a', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');
        if (!url) return;

        const form = $('#userFilterForm');

        if (userFilterXhr) {
            userFilterXhr.abort();
        }

        userFilterXhr = $.ajax({
            url: url,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                const panel = form.closest('.panel');
                panel.replaceWith(response);
            },
            error: function (xhr, status) {
                if (status === 'abort') return;
                console.error('Pagination error:', xhr.responseText);
            }
        });
    });


    let staffConvSearchTimer;
    let staffConvXhr = null;

    function filterStaffConversations(form) {
        if (staffConvXhr) staffConvXhr.abort();

        staffConvXhr = $.ajax({
            url: form.data('url'),
            type: 'GET',
            data: form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                form.closest('.panel').replaceWith(response);
            },
            error: function (xhr, status) {
                if (status === 'abort') return;
                console.error('Conversation filter error:', xhr.responseText);
            }
        });
    }

    $(document).on('keyup', '#staffConversationFilterForm input[name="search"]', function () {
        const form = $(this).closest('form');
        clearTimeout(staffConvSearchTimer);
        staffConvSearchTimer = setTimeout(() => filterStaffConversations(form), 300);
    });

    $(document).on('submit', '#staffConversationFilterForm', function (e) {
        e.preventDefault();
        filterStaffConversations($(this));
    });


    let staffResolvedSearchTimer;
    let staffResolvedXhr = null;

    function filterStaffResolved(form) {
        if (staffResolvedXhr) staffResolvedXhr.abort();

        staffResolvedXhr = $.ajax({
            url: form.data('url'),
            type: 'GET',
            data: form.serialize(),
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                form.closest('.panel').replaceWith(response);
            },
            error: function (xhr, status) {
                if (status === 'abort') return;
                console.error('Resolved filter error:', xhr.responseText);
            }
        });
    }

    $(document).on('keyup', '#staffResolvedFilterForm input[name="search"]', function () {
        const form = $(this).closest('form');
        clearTimeout(staffResolvedSearchTimer);
        staffResolvedSearchTimer = setTimeout(() => filterStaffResolved(form), 300);
    });

    $(document).on('change', '#staffResolvedFilterForm select[name="status"]', function () {
        filterStaffResolved($(this).closest('form'));
    });

    $(document).on('submit', '#staffResolvedFilterForm', function (e) {
        e.preventDefault();
        filterStaffResolved($(this));
    });

    // Pagination for both — reuse the same abort-safe pattern
    $(document).on('click', '#staffConversationsListContainer .pagination a, #staffResolvedTableContainer .pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (!url) return;

        const panel = $(this).closest('.panel');
        $.ajax({
            url: url,
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                panel.replaceWith(response);
            }
        });
    });



    function renderNotifications(data) {
        const badge = $('#notifBadge');
        const list = $('#notificationList');

        if (data.unread_count > 0) {
            badge.removeClass('d-none').text(data.unread_count > 9 ? '9+' : data.unread_count);
        } else {
            badge.addClass('d-none');
        }

        if (!data.notifications.length) {
            list.html('<div class="text-center text-muted small py-4">No notifications yet.</div>');
            return;
        }

        let html = '';
        data.notifications.forEach(function (n) {
            html += `
                <a href="${n.url || '#'}"
                data-url="${n.url || ''}"
                data-title="Complaint"
                data-notif-id="${n.id}"
                class="d-block text-decoration-none text-dark px-3 py-2 border-bottom notification-item ${n.is_read ? '' : 'bg-light'} ${n.url ? 'complaint-view-link' : ''}">
                    <div class="d-flex justify-content-between">
                        <strong class="small">${n.title}</strong>
                        ${!n.is_read ? '<span class="badge bg-primary" style="font-size:0.6rem;">New</span>' : ''}
                    </div>
                    <div class="small text-muted text-truncate">${n.message}</div>
                    <div class="small text-muted">${n.created_human}</div>
                </a>
            `;
        });
        list.html(html);
    }

    function loadNotifications() {
        $.ajax({
            url: '/notifications',
            type: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function (response) {
                renderNotifications(response);
            }
        });
    }

    $(function () {
        loadNotifications();
        setInterval(loadNotifications, 30000);
    });

    $(document).on('click', '.notification-item', function () {
        const id = $(this).data('notif-id');
        $.ajax({
            url: `/notifications/${id}/read`,
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function () {
                loadNotifications();
            }
        });
    });

    $(document).on('click', '#markAllReadBtn', function (e) {
        e.stopPropagation();
        $.ajax({
            url: '/notifications/mark-all-read',
            type: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function () {
                loadNotifications();
            }
        });
    });



});
