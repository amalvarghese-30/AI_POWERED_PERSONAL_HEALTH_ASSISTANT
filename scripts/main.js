document.getElementById('symptom-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const symptoms = Array.from(document.getElementById('symptoms').selectedOptions).map(option => option.value);
    const resultDiv = document.getElementById('result');

    // Simple AI logic (rule-based)
    if (symptoms.includes('fever') && symptoms.includes('cough')) {
        resultDiv.innerHTML = "<p>Possible Condition: Common Cold or Flu.</p>";
    } else if (symptoms.includes('headache') && symptoms.includes('fatigue')) {
        resultDiv.innerHTML = "<p>Possible Condition: Stress or Migraine.</p>";
    } else {
        resultDiv.innerHTML = "<p>No specific condition identified. Please consult a doctor.</p>";
    }
});

document.getElementById('recommendations-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const age = document.getElementById('age').value;
    const weight = document.getElementById('weight').value;
    const height = document.getElementById('height').value;
    const fitnessLevel = document.getElementById('fitness-level').value;
    const resultDiv = document.getElementById('recommendations-result');

    // Simple rule-based recommendations
    let diet = "Eat a balanced diet with fruits, vegetables, and lean proteins.";
    let fitness = "Start with 30 minutes of walking daily.";
    let mentalHealth = "Practice mindfulness and meditation for 10 minutes daily.";

    if (fitnessLevel === "intermediate") {
        fitness = "Include strength training 3 times a week.";
    } else if (fitnessLevel === "advanced") {
        fitness = "Engage in high-intensity interval training (HIIT) 4 times a week.";
    }

    resultDiv.innerHTML = `
    <h3>Your Recommendations:</h3>
    <p><strong>Diet:</strong> ${diet}</p>
    <p><strong>Fitness:</strong> ${fitness}</p>
    <p><strong>Mental Health:</strong> ${mentalHealth}</p>
  `;
});


document.getElementById('reminders-form').addEventListener('submit', function (e) {
    e.preventDefault();
    const medicationName = document.getElementById('medication-name').value;
    const dosage = document.getElementById('dosage').value;
    const time = document.getElementById('time').value;
    const remindersList = document.getElementById('reminders-list');

    // Add reminder to the list
    const reminderItem = document.createElement('div');
    reminderItem.innerHTML = `
    <p><strong>${medicationName}</strong> - ${dosage} at ${time}</p>
  `;
    remindersList.appendChild(reminderItem);

    // Clear the form
    document.getElementById('reminders-form').reset();
});


document.getElementById('contact-form').addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Thank you for contacting us! We will get back to you soon.');
    document.getElementById('contact-form').reset();
});