var app = new Vue({
    el: '#app',
    data: function () {
        return {
            message: "hello",
        }
    },
    methods: {
        test() {
            alert("tyrt");
            /*
            Swal.fire({
                title: 'Error!',
                text: 'Do you want to continue',
                icon: 'error',
                confirmButtonText: 'Cool'
            })
            */
        }
    }
});