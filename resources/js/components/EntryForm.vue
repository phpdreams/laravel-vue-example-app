<template>
    <div>
    <form method="POST" @submit.prevent="saveForm()">
        <input type="hidden" name="user_id" v-model="entry.user_id" />
        <div class="modal-header">
            <h5 class="modal-title">Add Balance Entry</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body d-flex flex-row align-items-center">
            <div class="input-wrapper col-md-6">
                <label for="label">Label</label>
                <input type="text" id="label" name="label" class="form-control" v-model="entry.label" required />
            </div>
            <div class="input-wrapper col-md-4">
                <label for="entry_time">Date / Time</label>
                <input type="datetime-local" id="entry_time" name="entry_time" class="form-control" v-model="entry.entry_time" required />
            </div>
            <div class="input-wrapper">
                <label for="amount">Amount</label>
                <input type="text" id="amount" name="amount" class="form-control" v-model="entry.amount" required />
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-cancel" data-dismiss="modal">Cancel</button>
            <input type="submit" class="btn btn-primary" value="Save Entry" />
        </div>
    </form>
    </div>
</template>

<script>
    export default {
        name: "EntryForm",
        props: [ 'user_id' ],
        data: function () {
            return {
                entry: {
                    user_id: this.user_id,
                    label: '',
                    entry_time: '',
                    amount: ''
                }
            }
        },
        mounted() {

        },
        methods: {
            saveForm() {
                var app = this;
                var newEntry = app.entry;
                axios.post('/api/v1/entries', newEntry)
                    .then(function (resp) {
                        app.$root.$emit('dorefresh');
                        // hide and empty the form
                        $('#add_entry').modal('hide');
                        app.entry.label = app.entry.entry_time = app.entry.amount = '';
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not add your entry");
                    });
            }

        }
    }
</script>
