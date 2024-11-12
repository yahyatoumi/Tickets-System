<template>
    <form>
        <p>Add item</p>
        <p>Item name: <input type="text" required v-model="itemName"></p>
        <p>How many: <input type="number" v-model="itemNumber"></p>
        <button type="submit">Add item</button>
    </form>
</template>

<script setup>
import { ref } from 'vue'

const itemName = ref(null)
const itemNumber = ref(null)
const shoppingList = ref([
    { name: 'Tomatoes', number: 5 }
])

function addItem() {
    if (itemName.value && itemNumber.value) {
        shoppingList.value.push({
            name: itemName.value,
            number: parseInt(itemNumber.value)
        });
        // Reset fields
        itemName.value = ''
        itemNumber.value = null
    }
}
</script>
