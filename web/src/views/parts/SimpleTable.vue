<template>
  <table class="table">
    <thead>
    <tr>
      <th>Name</th>
      <th>Value</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(val, name) in env" :key="name">
      <td>{{ name }}</td>
      <td><code>{{ val }}</code></td>
    </tr>
    </tbody>
  </table>
</template>

<script>
  export default {
    name: 'simple-table'
  }
</script>

<style scoped>

</style>
