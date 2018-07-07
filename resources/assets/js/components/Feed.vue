<template>
    <div>
        <incident
            v-if="incidents.length > 0"
            v-for="incident in incidents"
            :key="incident.id"
            :incident="incident"></incident>

        <p v-if="incidents.length == 0">
            No incidents currently ongoing
        </p>
    </div>
</template>

<script>
import Incident from './Incident';

export default {
    components: { Incident },
    data() {
        return {
            incidents: [],
        };
    },

    mounted() {
        axios.get('/api/incidents').then(response => this.incidents = response.data.data);
    },
};
</script>
