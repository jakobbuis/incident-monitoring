<template>
    <div>
        <nav class="navbar navbar-dark" :class="poll.failing ? 'bg-red' : 'bg-dark'">
            <div class="container">
                <a class="navbar-brand" href="#">
                    Incident monitoring
                    <span class="badge badge-pill badge-danger" v-if="ongoingIncidents.length > 0">
                        {{ ongoingIncidents.length }} incidents ongoing
                    </span>

                    <span class="badge badge-pill badge-success" v-if="ongoingIncidents.length === 0">
                        No incidents currently in progress
                    </span>
                </a>

                <div class="float-right">
                    Last update: {{ lastPoll ? lastPoll : 'unknown' }}
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="feed">
                <incident
                    v-for="incident in ongoingIncidents"
                    :key="incident.id"
                    :incident="incident"></incident>
                <hr>
                <incident
                    v-for="incident in resolvedIncidents"
                    :key="incident.id"
                    :incident="incident"></incident>
            </div>
        </div>
    </div>
</template>

<script>
import Incident from './Incident';

export default {
    components: { Incident },
    data() {
        return {
            incidents: [],
            poll: {
                lastSuccessful: null,
            },
        };
    },

    mounted() {
        this.pollChanges();
    },

    methods: {
        pollChanges() {
            Axios.get('/api/incidents').then(response => {
                this.incidents = response.data.data;
                this.poll.lastSuccessful = new Date;
                setTimeout(this.pollChanges, 5*1000);
            });
        }
    },

    computed: {
        ongoingIncidents() {
            return this.incidents.filter(i => i.resolved_at === null);
        },

        resolvedIncidents() {
            return this.incidents.filter(i => i.resolved_at !== null);
        },

        lastPoll() {
            const moment = this.poll.lastSuccessful;
            if (moment === null) {
                return null;
            }

            const d = `0${moment.getDate()}`.slice(-2);
            const m = `0${moment.getMonth()}`.slice(-2);
            const y = moment.getFullYear();
            const h = moment.getHours();
            const i = moment.getMinutes();
            const s = `0${moment.getSeconds()}`.slice(-2);

            return `${d}-${m}-${y} ${h}:${i}:${s}`;
        },
    }
};
</script>

<style scoped>
.navbar-brand .badge {
    margin-left: 2em;
}

.feed {
    position: relative;
}

.navbar .float-right {
    color: white;
}
</style>
