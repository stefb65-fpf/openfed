<div class="modal fade" id="modal-infos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 750px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Informations</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="body-modal-infos">
            </div>
            <div class="modal-footer" id="footer-modal-infos">
                <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-forgot-identifiants" tabindex="-1" aria-labelledby="forgotModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 750px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotModalLabel">Identifiants oubliés</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Indiquez votre adresse email et nous vous ferons parvenir vos identifiants
                <form>
                    <input type="email" class="form-control" id="mailForgotId" placeholder="name@example.com" maxlength="70">
                </form>
            </div>
            <div class="modal-footer" id="footer-modal-infos">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" id="validForgotId">Valider</button>
            </div>
        </div>
    </div>
</div>
