<template>
    <div class="card mb-3" :class="color">
        <div class="card-header">
            {{ incident.website.name }}
            <div class="float-right">
                <span class="badge badge-pill badge-light">{{ status }}</span>
            </div>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>URL</strong>
                <a :href="incident.website.url" target="_blank" rel="noopener">
                    {{ incident.website.url }}
                </a>
            </li>
            <li class="list-group-item">
                <strong>Incident</strong>
                {{ cause }}
            </li>
            <li class="list-group-item">
                <strong>Last change</strong>
                {{ statusSince }}
            </li>

            <!-- Custom properties -->
            <li class="list-group-item" v-if="incident.type === 'SiteDown'">
                <strong>HTTP response code</strong>
                {{ incident.data.http_status_code || 'unknown' }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ['incident'],

    computed: {
        color() {
            if (this.incident.resolved_at !== null) {
                return 'bg-informational';
            }

            if (this.incident.level === 1) {
                return 'bg-danger text-white';
            }
            if (this.incident.level === 2) {
                return 'bg-warning text-white';
            }
            if (this.incident.level === 3) {
                return 'bg-success text-white';
            }
        },

        status() {
            return this.incident.resolved_at === null ? 'Ongoing' : 'Resolved';
        },

        statusSince() {
            // Determine which date to display
            let point = this.incident.resolved_at;
            if (this.incident.resolved_at === null) {
                point = this.incident.detected_at;
            }

            // Display the time on the same day, otherwise display the date too
            point = new Date(point);
            if ((new Date).toDateString() === point.toDateString()) {
                return point.toLocaleTimeString();
            }
            return point.toLocaleString();
        },

        cause() {
            if (this.incident.type === 'SiteDown') {
                return 'HTTP response status is higher than 400';
            } else if (this.incident.type === 'CertificateError') {
                return 'SSL-certificate failed validation';
            }
        },
    },
};
</script>

<style scoped>
li {
    color: black;
}
li strong {
    display: inline-block;
    width: 12em;
}
</style>
