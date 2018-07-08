<template>
    <div>
        <p v-if="incidents.length == 0">
            No incidents currently ongoing
        </p>
        <template v-else>
            <incident
                v-for="incident in ongoingIncidents"
                :key="incident.id"
                :incident="incident"></incident>
            <hr>
            <incident
                v-for="incident in resolvedIncidents"
                :key="incident.id"
                :incident="incident"></incident>
        </template>
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

    computed: {
        ongoingIncidents() {
            return this.incidents.filter(i => i.resolved_at === null);
        },

        resolvedIncidents() {
            return this.incidents.filter(i => i.resolved_at !== null);
        },
    }
};
</script>
