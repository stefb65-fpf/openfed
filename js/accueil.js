$('#validationReglementInscription').on('click', function () {
    if ($(this).is(':checked') && $('#mailInscription').val() != '' && $('#passwordInscription').val().length > 7) {
        $('#btnValidInscription').removeClass('disabled');
    } else {
        $('#btnValidInscription').addClass('disabled');
    }
})
$('#btnValidInscription').on('click', function() {
    if ($('#mailInscription').val() == '' || $('#prenomInscription').val() == '' || $('#nomInscription').val() == '') {
        $('#body-modal-infos').html("Vous devez renseigner tous les champs pour vous inscrire : nom, prénom et email")
        $('#modal-infos').modal('show')
        return
    }
    const url = $('#route_for_inscription_openfed').html();
    const data = {
        email : $('#mailInscription').val(),
        prenom : $('#prenomInscription').val(),
        nom : $('#nomInscription').val(),
        pass: $('#passwordInscription').val()
    };
    axios.post(url, data)
        .then(function (res) {
            let message;
            switch (res.data.code) {
                case '0' : message = "Votre demande d'inscription a bien été prise en compte. Un email vient de vous être envoyé. Cliquez sur le lien contenu dans le mail afin de valider votre inscription."; break;
                case '10' : message = "L'adresse email renseignée est incorrecte"; break;
                case '20' : message = "L'adresse email renseignée existe déjà dans notre base de données et correspond à un adhérent FPF. Si vous êtes adhérent FPF, veuillez vous connecter avec vos identifiants."; break;
                case '30' : message = "Cette adresse email existe déjà dans notre base de données. Vous pouvez vous connecter avec ou récupérer vos identifiant en cliquant sur 'mot de passe oublié'"; break;
                // case '30' : message = "L'adresse email renseignée existe déjà dans notre base de données et correspond à un utilisateur non adhérent à la FPF. Si vous avez déjà participé à un concours open, vous pouvez récupérer vos identifiant en cliquant sur 'identifiants oubliés'"; break;
                default: break;
            }
            $('#body-modal-infos').html(message)
            $('#modal-infos').modal('show')
        })
        .catch(function (err) {
            console.log(err)
            return
        })
})

$('#forgotId').on('click', function() {
    $('#mailForgotId').val('');
    $('#modal-forgot-identifiants').modal('show');
})

$('#validForgotId').on('click', function() {
    if ($('#mailForgotId').val() == '') {
        alert("L'adresse email doit être renseignée"); return;
    }
    const url = $('#route_for_forgot_id').html();
    const data = {
        email : $('#mailForgotId').val()
    };
    axios.post(url, data)
        .then(function (res) {
            $('#modal-forgot-identifiants').modal('hide');
            let message;
            switch (res.data.code) {
                case '0' : message = "Un email contenant vos identifiants vient de vous être envoyé"; break;
                case '10' : message = "L'adresse email renseignée est incorrecte"; break;
                case '20' : message = "L'adresse email renseignée n'existe pas dans notre base de données."; break;
                default: break;
            }
            $('#body-modal-infos').html(message)
            $('#modal-infos').modal('show')
        })
        .catch(function (err) {
            console.log(err)
            return
        })
})


// $('#loginOpen').on('keyup', function() {
//     if ($('#loginOpen').val().length == 12 && $('#passwordOpen').val().length == 5) {
//         $('#btnConnexionOpen').removeClass('disabled');
//     } else {
//         $('#btnConnexionOpen').addClass('disabled');
//     }
// })
//
// $('#passwordOpen').on('keyup', function() {
//     console.log($(this).val().length);
//     if ($('#loginOpen').val().length == 12 && $('#passwordOpen').val().length == 5) {
//         $('#btnConnexionOpen').removeClass('disabled');
//     } else {
//         $('#btnConnexionOpen').addClass('disabled');
//     }
// })

$('#btnConnexionOpen').on('click', function() {
    const url = $('#route_for_check_login_open').html();
    const data = {
        identifiant : $('#loginOpen').val(),
        password : $('#passwordOpen').val()
    };
    axios.post(url, data)
        .then(function (res) {
            if(res.data.code == '0') {
                const newurl = 'https://open.federation-photo.fr/inscription'
                $(location).attr('href', newurl)
            } else {
                let message;
                switch (res.data.code) {
                    case '10' : message = "Une erreur est survenue lors de votre authentification"; break;
                    case '11' : message = res.data.message; break;
                    case '20' : message = "Vous avez un identifiant FPF mais aucune adresse email n'est renseignée pour cet identifiant. Veuillez vous connecter à la base en ligne et indiquez une adresse email afin de pouvoir participer au concours."; break;
                    default: break;
                }
                $('#body-modal-infos').html(message)
                $('#modal-infos').modal('show')
            }
        })
        .catch(function (err) {
            console.log(err)
            return
        })
})
