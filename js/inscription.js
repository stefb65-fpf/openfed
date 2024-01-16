$('div[name=browse]').on('click', function() {
    const ref = $(this).data('ref');
    $('#browse').data('ref', ref);
    $('#browse').trigger('click');
})
let uploader = new plupload.Uploader({
    runtimes : 'html5',
    browse_button : 'browse',
    drop_element: 'browse',
    url: 'https://open.federation-photo.fr/photos/upload/' + $('#browse').data('identifiant') + '/' + $('#browse').data('compet') + '/',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    init: {
        PostInit: function() {
            $('div[name=ajaxLoader]').hide();
            $('div[name=textUpload]').show();
        },
        FilesAdded: function(up, files) {
            uploader.settings.url += $('#browse').data('ref')
            uploader.start();
        },
        UploadProgress: function(up, file) {
            const ref = $('#browse').data('ref')
            $('#textUpload' + ref).hide()
            $('#ajaxLoader' + ref).show()
        },

//    , si le code est 20 ou 30, on affiche un message pour dire que ce n'est pas OK.
        FileUploaded: function(up, file, reponse) {
            let i = 1;
            $.each(reponse, function(data, value){
                const ref = $('#browse').data('ref')
                $('#ajaxLoader' + ref).hide()
                // pour être sur de ne récupérer qu'un seul retour d'appel uploader
                if (i === 1) {
                    // on parse en json la réponse envoyé par le controleur en array
                    const datarep = $.parseJSON(value);
                    if (datarep.code == '0') {
                        let chaine = "<span id='uploadOpen" + ref + "' style='display: none'>";
                        chaine += datarep.name;
                        chaine += "</span>";
                        chaine += "<img src='" + datarep.url + datarep.name + "' style='" + datarep.chaine + "'>";
                        $('#browse' + ref).html(chaine);
                        $('#register_photo' + ref).removeClass('disabled');
                    } else {
                        switch (datarep.code) {
                            case '10': alert("Le poids de votre image est supérieur à 3Mo"); break;
                            case '20': alert("Le format de votre image est incorrect. Format jpg accepté"); break;
                            case '30': alert("Aucune des deux dimensions n'a la valeur maximale"); break;
                            case '31': alert("La largeur de votre photo est supérieure à 1920 pixels"); break;
                            case '32': alert("La hauteur de votre photo est supérieure à 1920 pixels"); break;
                            default: alert("Une erreur s'est produite lors du téléchargement"); break;
                        }
                    }
                }
                i++;
            })
        },
    }
});
uploader.init();


$('button[name=register_photo]').on('click', function() {
    const ref = $(this).data('ref')
    const compet = $('#browse').data('compet')
    const identifiant = $('#browse').data('identifiant')
    const photo = $('#uploadOpen' + ref).html()

    if ($('#titre_oeuvre_' + ref).val() == '') {
        $('#body-modal-infos').html("Veuillez renseigner un titre pour votre photo")
        $('#modal-infos').modal('show')
        return
    }
    const data = {
        titre: $('#titre_oeuvre_' + ref).val(),
        identifiant : identifiant,
        compet : compet,
        photo : photo
    };
    const url = $('#route_for_save_photo_open').html();
    axios.post(url, data)
        .then(function (res) {
            let message;
            console.log(res.data)
            if(res.data.code == '0') {
                message = "Votre oeuvre a bien été enregistrée";
                $('#register_photo' + ref).hide()
                $('#delete_photo' + ref).data('ref', res.data.num_photo)
                $('#delete_photo' + ref).show()
            } else {
                message = "Un problème est survenu lors de l'enregistrement de votre oeuvre";
            }
            $('#body-modal-infos').html(message)
            $('#modal-infos').modal('show')
        })
        .catch(function (err) {
            console.log(err)
            return
        })
})

$('button[name=delete_photo]').on('click', function() {
    const photo = $(this).data('ref')
    const compet = $('#browse').data('compet')
    const identifiant = $('#browse').data('identifiant')
    const data = {
        compet: compet,
        identifiant: identifiant,
        photo: photo,
    };
    const url = $('#route_for_delete_photo_open').html();
    axios.post(url, data)
        .then(function (res) {
            if(res.data.code == '0') {
               $(location).attr('href', $(location).attr('href'))
            } else {
                $('#body-modal-infos').html("Une erreur est survenue lors de la suppression de votre oeuvre")
                $('#modal-infos').modal('show')
            }
        })
        .catch(function (err) {
            console.log(err)
            return
        })
})


$('input[name=titre_oeuvre]').on('keydown', function(e) {
    if (e.which === 13) {
        const titre = $(this).val()
        const compet = $('#browse').data('compet')
        const identifiant = $('#browse').data('identifiant')
        const photo = $(this).data('ref')
        const url = $('#route_for_update_title_photo_open').html();
        const data = {
            titre: titre,
            compet: compet,
            identifiant: identifiant,
            ref: photo
        }
        axios.post(url, data)
            .then(function (res) {
                let message;
                if(res.data.code == '0') {
                    message = "Le titre de votre oeuvre a été mis à jour";
                } else {
                    message = "Une erreur est survenue lors de la suppression de votre oeuvre";
                }
                $('#body-modal-infos').html(message)
                $('#modal-infos').modal('show')
            })
            .catch(function (err) {
                console.log(err)
                return
            })
    }
})
