<script>
export default {
  props: {
    links: Array
  }
};
</script>

<template>
  <div>
    <Component
        :is="link.url ? 'a' : 'span'"
        v-for="link in links"
        :href="link.url"
        :innerHTML="link.label"
        class="px-1"
        :class="{ 'text-gray-500': ! link.url, 'font-bold' : link.active }"
    />
  </div>
</template>