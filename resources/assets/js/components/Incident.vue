<template>
    <div class="card mb-3" :class="color">
        <div class="card-header">{{ incident.title }}</div>
        <div class="card-body">
            <p class="card-text">{{ incident.description }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['incident'],

    computed: {
        color() {
            if (this.incident.level === 1) {
                return 'bg-danger text-white';
            }
            if (this.incident.level === 2) {
                return 'bg-warning text-white';
            }
            if (this.incident.level === 3) {
                return 'bg-success text-white';
            }
            return 'bg-informational';
        },
    },
};
</script>
