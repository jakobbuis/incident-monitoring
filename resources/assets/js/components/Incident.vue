<template>
    <div class="card mb-3" :class="color">
        <div class="card-header">
            <span class="badge badge-pill badge-light badge-static">{{ problem }}</span>

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

        problem() {
            if (this.incident.type === 'SiteDown') {
                return 'Website broken';
            } else if (this.incident.type === 'CertificateError') {
                return 'SSL problem';
            }
        },

        status() {
            const status = this.incident.resolved_at === null ? 'Ongoing' : 'Resolved';
            return `${status} since ${this.statusSince}`;
        },

        statusSince() {
            // Determine which date to display
            let point = this.incident.resolved_at;
            if (this.incident.resolved_at === null) {
                point = this.incident.detected_at;
            }

            // Display the time on the same day, otherwise display the date too
            point = new Date(point);

            const d = `0${point.getDate()}`.slice(-2);
            const m = `0${point.getMonth() + 1}`.slice(-2);
            const y = point.getFullYear();
            const h = `0${point.getHours()}`.slice(-2);
            const i = `0${point.getMinutes()}`.slice(-2);

            return `${d}-${m}-${y} ${h}:${i}`;
        },

        cause() {
            if (this.incident.type === 'SiteDown') {
                return 'HTTP response status is higher than 400';
            } else if (this.incident.type === 'CertificateError') {
                return 'SSL-certificate failed validation';
            }
            return 'Unknown incident';
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

.badge-static {
    width: 9em;
    margin-right: 2em;
}
</style>
