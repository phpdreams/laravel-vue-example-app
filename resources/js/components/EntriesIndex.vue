<template>
    <div class="entries">
        <div v-for="(day, key) in entries" v-if="key != 'morePages'">
            <div class="day-wrapper d-flex flex-row align-items-center">
                <div class="day-title mr-auto">{{ day.title }}</div>
                <div class="day-balance" v-html="displayAmount(day.balance)"></div>
            </div>
            <div class="card" v-for="(entry, index) in day.entries" :id="'entry-' + entry.id">
                <div class="entry-content d-flex flex-row align-items-center">
                    <div class="entry-info mr-auto">
                        <div class="entry-label">{{ entry.label }}</div>
                        <div class="entry-time">{{ entry.entry_time }}</div>
                    </div>
                    <div class="entry-actions hidden mr-3">
                        <span href="#" class="d-inline-block mr-3" @click.stop="editEntry(entry.id)">
                            Edit
                        </span>
                        <span href="#" class="d-inline-block mr-3 text-danger" @click.stop="deleteEntry(entry.id)">
                            Delete
                        </span>
                    </div>
                    <div class="entry-amount" v-html="displayAmount(entry.amount)"></div>
                </div>
                <div class="edit-wrapper" v-if="active_editor == entry.id">
                    <edit-form :user_id="user_id" :entry_id="entry.id" :label="entry.label" :entry_time="entry.entry_time" :amount="entry.amount"></edit-form>
                </div>
            </div>
        </div>
        <div class="pagination d-flex flex-row align-items-center">
            <div class="prev-page" v-if="morePages">
                <button class="btn btn-outline-primary" @click="prevPage">Previous</button>
            </div>
            <div class="next-page ml-auto" v-if="page > 1">
                <button class="btn btn-outline-primary" @click="nextPage">Next</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [ 'user_id' ],
        data: function () {
            return {
                entries: [],
                active_editor: null,
                page: 1,
                morePages: null,
            }
        },
        mounted() {
            this.loadEntries();
            this.$root.$on('dorefresh', this.loadEntries);
        },
        methods: {
            loadEntries() {
                var app = this;
                axios.get('/api/v1/entries?page=' + app.page)
                    .then(function (resp) {
                        app.entries = resp.data;
                        app.morePages = app.entries.morePages;
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not load entries");
                    });
            },
            nextPage() {
                if(this.page > 0) this.page--;
                this.loadEntries();
            },
            prevPage() {
                if(this.page > 0) this.page++;
                this.loadEntries();
            },
            editEntry(entry_id) {
                var app = this;
                app.active_editor = entry_id;
            },
            cancelEditEntry() {
                var app = this;
                app.active_editor = null;
            },
            deleteEntry(id, index) {
                if (confirm("Do you really want to delete it?")) {
                    var app = this;
                    axios.delete('/api/v1/entries/' + id)
                        .then(function (resp) {
                            app.$root.$emit('dorefresh');
                        })
                        .catch(function (resp) {
                            alert("Could not delete company");
                        });
                }
            },
            displayAmount(amount) {
                var display = amount.replace(/(-?)([\,\d]*\.)(\d*)/,'$1\$ $2<span class="cents">$3</span>');
                if(amount < 0) {
                    return '<span class="negative">' + display + '</span>';
                }
                return display;
            }
        }
    }
</script>
