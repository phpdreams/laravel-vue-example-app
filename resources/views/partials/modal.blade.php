
<div class="modal" tabindex="-1" role="dialog" id="add_entry">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <entry-form :user_id="{{ Auth::user()->id }}"></entry-form>
        </div>
    </div>
</div>
