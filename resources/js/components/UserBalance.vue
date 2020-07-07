<template>
    <section class="app-info-bar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9 row align-items-center justify-content-between">
                    <div class="info-actions">
                        <span class="heading">Your Balance</span>
                        <button class="btn btn-primary info-btn" data-toggle="modal" data-target="#add_entry"><i class="fa fa-plus"></i> Add Entry</button>
                        <button class="btn btn-primary info-btn"><i class="fa fa-upload"></i> Import CSV</button>
                    </div>
                    <div class="balance-wrapper">
                        <div class="title">Total Balance</div>
                        <div class="balance" v-html="balance"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "UserBalance",
        props: [ 'userid' ],
        data: function () {
            return {
                balance: ''
            }
        },
        mounted() {
            this.getUserBalance();
            this.$root.$on('dorefresh', this.getUserBalance);
        },
        methods: {
            getUserBalance() {
                var app = this;
                axios.get('/api/v1/users/' + app.userid + '/balance')
                    .then(function (resp) {
                        app.balance = app.displayAmount(resp.data);
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Could not load balance");
                    });
            },
            displayAmount(amount) {
                var display = amount.replace(/(-?)([\,\d]*\.)(\d*)/,'$1\$ $2<span class="cents">$3</span>');
                if(amount.replace(/\,/,'') < 0) {
                    return '<span class="balance-negative">' + display + '</span>';
                }
                return display;
            }
        }
    }
</script>

<style scoped>

</style>
