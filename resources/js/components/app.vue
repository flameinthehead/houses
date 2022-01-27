<template>
    <div>
        <form ref="form" v-on:submit="search">
            <input name="name" v-model="name" placeholder="Name" />
            <input name="price_form" v-model="price_from" placeholder="Price from" />
            <input name="price_to" v-model="price_to" placeholder="Price to" /><br />
            <input name="bedrooms" v-model="bedrooms" placeholder="Bedrooms" />
            <input name="bathrooms" v-model="bathrooms" placeholder="Bathrooms" />
            <input name="storeys" v-model="storeys" placeholder="Storeys" />
            <input name="garages" v-model="garages" placeholder="Garages" />
            <button type="submit">Search</button>
        </form>
        <p v-if="error_message">{{ error_message }}</p>
        <p v-if="nothing_found">Nothing found!</p>

        <table v-if="houses.length">
            <thead>
                <th>Name</th>
                <th>Price</th>
                <th>Bedrooms</th>
                <th>Bathrooms</th>
                <th>Storeys</th>
                <th>Garages</th>
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
        search(e) {
            if (typeof e != 'undefined'){
                e.preventDefault();
            }

            const formData = new FormData(this.$refs['form']); // reference to form element
            const data = {}; // need to convert it before using not with XMLHttpRequest
            for (let [key, val] of formData.entries()) {
                Object.assign(data, { [key]: val })
            }

            var output = this;
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
                    var error_messages = [];
                    if (error.response && error.response.data) {
                        var data = error.response.data;
                        console.log(data);
                        output.error_message = error_messages;
                    }
                })
        }
    },
    data() {
        return {
            success: false,
            error_message: null,
            nothing_found: false,
            houses: [],
            name: null,
            price_from: null,
            price_to: null,
            bedrooms: null,
            bathrooms: null,
            storeys: null,
            garages: null,
        };
    },
};
</script>
