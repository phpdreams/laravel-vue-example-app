<template>
    <form method="POST" class="edit-form" @submit.prevent="saveForm()">
        <input type="hidden" name="user_id" :value="userid" />
        <input type="hidden" name="entry_id" :value="entry_id" />
        <div class="d-flex flex-row align-items-center">
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
            <button type="button" class="btn btn-cancel" @click="cancelEdit()">Cancel</button>
            <input type="submit" class="btn btn-primary" value="Save Entry" />
        </div>
    </form>
</template>

<script>
    export default {
        name: "EditForm",
        props: [
            'userid',
            'entry_id',
            'label',
            'entry_time',
            'amount'
        ],
        data: function () {
            return {
                entry: {
                    user_id: this.user_id,
                    entry_id: this.entry_id,
                    label: this.label,
                    entry_time: this.$moment(this.entry_time).format(this.$moment.HTML5_FMT.DATETIME_LOCAL),
                    amount: this.amount
                }
            }
        },
        mounted() {

        },
        methods: {
            cancelEdit(){
                this.$parent.cancelEditEntry();
            },
            saveForm() {
                var app = this;
                var newEntry = app.entry;
                axios.patch('/api/v1/entries/' + app.entry_id, newEntry)
                    .then(function (resp) {
                        app.$root.$emit('dorefresh');
                        app.$parent.cancelEditEntry();
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not update entry");
                    });
            }
        }
    }
</script>
