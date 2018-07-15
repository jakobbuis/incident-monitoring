<template>
    <div class=container>
        <nav class="navbar navbar-dark" :class="poll.failing ? 'bg-red' : 'bg-dark'">
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
                <span class="badge badge-pill badge-light" v-if="poll.failing">
                    Updating failed, last successful
                    {{ poll.lastSuccessful ? poll.lastSuccessful.toLocaleString() : 'unknown' }}
                </span>
            </div>
        </nav>

        <div class="feed">
            <div class="outdated-overlay" v-if="poll.failing"></div>

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
                failing: false,
                lastSuccessful: null,
            },
        };
    },

    mounted() {
        this.pollChanges();
    },

    methods: {
        pollChanges() {
            axios.get('/api/incidents').then(response => {
                this.incidents = response.data.data;

                this.poll.failing = false;
                this.poll.lastSuccessful = new Date;

                setTimeout(this.pollChanges, 5*1000);
            }).catch(() => {
                this.poll.failing = true;

                setTimeout(this.pollChanges, 30*1000);
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
    }
};
</script>

<style scoped>
.navbar-brand .badge {
    margin-left: 2em;
}

.bg-red {
    background-color: #dc3545 !important;
}

.feed {
    position: relative;
}

.outdated-overlay {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 10;
    background-color: rgba(0,0,0,0.5);
}
</style>
