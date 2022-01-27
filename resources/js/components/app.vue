<template>
    <div class="container-sm">
        <h3 class="text-center">Houses</h3>
        <form ref="form" v-on:submit="search">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" name="name" class="form-control" v-bind:class="{ 'is-invalid': errMess && errMess.name }" />
                <span v-if="errMess && errMess.name" class="invalid-feedback" role="alert">
                    <strong>{{ errMess.name[0] }}</strong>
                </span>
            </div>
            <div class="mb-3">
                <label for="price_from" class="form-label">Price from</label>
                <input id="price_from" name="price_from" class="form-control" v-bind:class="{ 'is-invalid': errMess && (errMess.price_from || errMess.price_range) }" />
                <span v-if="errMess && errMess.price_from" class="invalid-feedback" role="alert">
                    <strong>{{ errMess.price_from[0] }}</strong>
                </span>
            </div>

            <div class="mb-3">
                <label for="price_to" class="form-label">Price to</label>
                <input id="price_to" name="price_to" class="form-control" v-bind:class="{ 'is-invalid': errMess && (errMess.price_to || errMess.price_range) }" />
                <span v-if="errMess && errMess.price_to" class="invalid-feedback" role="alert">
                    <strong>{{ errMess.price_to[0] }}</strong>
                </span>
                <span v-if="errMess && errMess.price_range" class="invalid-feedback" role="alert">
                    <strong>{{ errMess.price_range[0] }}</strong>
                </span>
            </div>

            <div class="mb-3">
                <label for="bedrooms" class="form-label">Bedrooms</label>
                <input id="bedrooms" name="bedrooms" class="form-control" v-bind:class="{ 'is-invalid': errMess && errMess.bedrooms }" />
                <span v-if="errMess && errMess.bedrooms" class="invalid-feedback" role="alert">
                    <strong>{{ errMess.bedrooms[0] }}</strong>
                </span>
            </div>

            <div class="mb-3">
                <label for="bathrooms" class="form-label">Bathrooms</label>
                <input id="bathrooms" name="bathrooms" class="form-control" v-bind:class="{ 'is-invalid': errMess && errMess.bathrooms }" />
                <span v-if="errMess && errMess.bathrooms" class="invalid-feedback" role="alert">
                    <strong>{{ errMess.bathrooms[0] }}</strong>
                </span>
            </div>

            <div class="mb-3">
                <label for="storeys" class="form-label">Storeys</label>
                <input id="storeys" name="storeys" class="form-control" v-bind:class="{ 'is-invalid': errMess && errMess.storeys }" />
                <span v-if="errMess && errMess.storeys" class="invalid-feedback" role="alert">
                    <strong>{{ errMess.storeys[0] }}</strong>
                </span>
            </div>

            <div class="mb-3">
                <label for="garages" class="form-label">Garages</label>
                <input id="garages" name="garages" class="form-control" v-bind:class="{ 'is-invalid': errMess && errMess.garages }" />
                <span v-if="errMess && errMess.garages" class="invalid-feedback" role="alert">
                    <strong>{{ errMess.garages[0] }}</strong>
                </span>
            </div>

            <button type="submit" class="btn btn-primary">Search</button>
            <input class="btn btn-secondary" v-on:click="reset" type="reset" value="Reset">
        </form>

        <div v-if="nothing_found" class="alert alert-primary mt-3" role="alert">
            Nothing found!
        </div>

        <div v-if="errMess" class="alert alert-danger mt-3" role="alert">
            Search error
        </div>

        <hr v-if="houses.length" />
        <table v-if="houses.length" class="table">
            <thead>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Bedrooms</th>
                <th scope="col">Bathrooms</th>
                <th scope="col">Storeys</th>
                <th scope="col">Garages</th>
            </thead>
            <tr v-for="house in houses">
                <td>{{ house.name }}</td>
                <td>{{ house.price }}</td>
                <td>{{ house.bedrooms }}</td>
                <td>{{ house.bathrooms }}</td>
                <td>{{ house.storeys }}</td>
                <td>{{ house.garages }}</td>
            </tr>
        </table>
    </div>
</template>
<script>
export default {
    name: "Houses",
    mounted() {
         this.search();
    },
    methods: {
        search(e, reset = false) {
            if (typeof e != 'undefined'){
                e.preventDefault();
            }

            const formData = new FormData(this.$refs['form']);
            const data = {};
            if (!reset){
                for (let [key, val] of formData.entries()) {
                    Object.assign(data, { [key]: val })
                }
            }

            var output = this;
            output.nothing_found = false;
            output.errMess = null;
            axios
                .get("/houses", {params: data})
                .then(function(response) {
                    var data = response.data;
                    output.success = data.success;
                    output.houses = data.houses;

                    if (data.success && !data.houses.length){
                        output.nothing_found = true;
                    }
                })
                .catch(function(error) {
                    output.houses = [];
                    if (error.response && error.response.data) {
                        var data = error.response.data;
                        output.errMess = data.message;
                    }
                })
        },

        reset(e) {
            this.$refs.form.reset();
            this.search(e, true);
            this.$refs.form.reset();
        }
    },
    data() {
        return {
            success: false,
            errMess: null,
            nothing_found: false,
            houses: [],
        };
    },
};
</script>
