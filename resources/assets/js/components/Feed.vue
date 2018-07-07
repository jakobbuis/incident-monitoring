<template>
    <div>
        <incident v-for="incident in incidents" :key="incident.id" :incident="incident">
        </incident>
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
    },
};
</script>
