<template>
    <div class="card mb-3" :class="color">
        <div class="card-header">
            {{ title }}
            <div class="float-right">
                <span class="badge badge-pill badge-light">{{ status }}</span>
            </div>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>URL:</strong>
                {{ incident.website.url }}
            </li>
            <li class="list-group-item">
                <strong>Last change:</strong>
                {{ statusSince }}
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

        title() {
            if (this.incident.type === 'SiteDown') {
                return `Website down: ${this.incident.website.name}`;
            }
            return 'Unknown incident';
        },

        description() {

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
        }
    },
};
</script>

<style scoped>
li {
    color: black;
}
li strong {
    display: inline-block;
    width: 10em;
}
</style>
