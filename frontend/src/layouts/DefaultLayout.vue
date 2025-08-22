<template>
    <div>
      <header>
        <h1>Neighbourhood Reservations</h1>
      </header>
      <main>
        <slot />
      </main>
    </div>
  </template>
  
  <script setup lang="ts">
  // vacío
  
  </script>
  
  <style scoped>
  header {
    background-color: #42b983;
    color: white;
    padding: 1rem;
  }
  </style>