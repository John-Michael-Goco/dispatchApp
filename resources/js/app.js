/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({});

import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

// Request notification permission
if (Notification.permission !== "granted" && Notification.permission !== "denied") {
    Notification.requestPermission();
}

// Listen for new emergencies
window.Echo.channel('emergencies')
    .listen('new-emergency', (e) => {
        console.log('New emergency received:', e);
        
        // Show browser notification if permission is granted
        if (Notification.permission === "granted") {
            new Notification("New Emergency", {
                body: `New emergency reported: ${e.incident}`,
                icon: '/images/emergency-icon.png' // Make sure to add this icon to your public folder
            });
        }

        // You can also trigger a custom event that your Vue components can listen to
        window.dispatchEvent(new CustomEvent('new-emergency', { detail: e }));
    })
    .error((error) => {
        console.error('Pusher error:', error);
    });

app.mount('#app');
