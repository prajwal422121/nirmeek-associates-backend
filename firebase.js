import { getApps, initializeApp } from "firebase/app";
import { getDatabase, ref, push, onValue } from "firebase/database";

const dynamicConfig = {
  apiKey: "AIzaSyBgiG0yUHxZfctZarWg_U4Oq4StH2H1dGI",
  authDomain: "dynamic-745ae.firebaseapp.com",
  databaseURL: "https://dynamic-745ae-default-rtdb.firebaseio.com",
  projectId: "dynamic-745ae",
  storageBucket: "dynamic-745ae.appspot.com",
  messagingSenderId: "853829413975",
  appId: "1:853829413975:web:e0b130efeed9a4b7730712"
};

const adminConfig = {
  apiKey: "AIzaSyBZucOkcX5uNu-tejhXpGgHnh8deRNwDno",
  authDomain: "admindashboard-ebb97.firebaseapp.com",
  databaseURL: "https://admindashboard-ebb97-default-rtdb.firebaseio.com",
  projectId: "admindashboard-ebb97",
  storageBucket: "admindashboard-ebb97.firebasestorage.app",
  messagingSenderId: "839931753288",
  appId: "1:839931753288:web:0eb5241df4c0b02aba797d",
  measurementId: "G-R3X1RSR1JD"
};

const apps = getApps();

const dynamicApp = !apps.some(app => app.name === 'dynamicApp') 
  ? initializeApp(dynamicConfig, 'dynamicApp')
  : apps.find(app => app.name === 'dynamicApp');

const adminApp = !apps.some(app => app.name === 'adminApp') 
  ? initializeApp(adminConfig, 'adminApp')
  : apps.find(app => app.name === 'adminApp');

const dynamicDB = getDatabase(dynamicApp);
const adminDB = getDatabase(adminApp);

export { dynamicDB, adminDB, ref, push, onValue };