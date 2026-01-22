// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyB46CO4zextyjoCat4_NSIuwIjYjcwJQfk",
    authDomain: "kashmiristitch.firebaseapp.com",
    projectId: "kashmiristitch",
    storageBucket: "kashmiristitch.firebasestorage.app",
    messagingSenderId: "706134153827",
    appId: "1:706134153827:web:77075829a79d141a90b656",
    measurementId: "G-3FQFTM1T1H"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const auth = firebase.auth();
const provider = new firebase.auth.GoogleAuthProvider();

function signInWithGoogle() {
    auth.signInWithPopup(provider)
        .then((result) => {
            // This gives you a Google Access Token. You can use it to access the Google API.
            // var token = result.credential.accessToken;
            // The signed-in user info.
            var user = result.user;
            var googleIdToken = result.credential.idToken;
            var profile = result.additionalUserInfo ? result.additionalUserInfo.profile : {};

            var firstname = profile.given_name || (user.displayName ? user.displayName.split(' ')[0] : 'Google');
            var lastname = profile.family_name || (user.displayName ? user.displayName.split(' ').slice(1).join(' ') : 'User');
            var picture = profile.picture || user.photoURL || '';

            if (!googleIdToken) {
                // Fallback or error if scopes didn't return idToken
                // Try forcing a refresh or just alert
                alert('Could not retrieve Google ID Token. Please try again.');
                return;
            }

            // Send token to your backend via HTTPS
            fetch('google_login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    token: googleIdToken,
                    firstname: firstname,
                    lastname: lastname,
                    picture: picture
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'index.php';
                    } else {
                        alert('Login Failed: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An server error occurred during login');
                });
        })
        .catch((error) => {
            // Handle Errors here.
            var errorCode = error.code;
            var errorMessage = error.message;
            console.error(errorCode, errorMessage);
            alert('Google Sign In Failed: ' + errorMessage);
        });
}

// Bind to button if it exists
document.addEventListener('DOMContentLoaded', function () {
    const googleBtn = document.getElementById('googleLoginBtn');
    if (googleBtn) {
        googleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            signInWithGoogle();
        });
    }
});
