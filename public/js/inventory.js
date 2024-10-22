function openSerialModal(productModelName, serialNumbers) {
    console.log("Modal opened for:", productModelName, "with serial numbers:", serialNumbers);
  
      document.getElementById('productModelName').innerText = productModelName;
      const serialNumberList = document.getElementById('serialNumberList');
      serialNumberList.innerHTML = '';
  
      // Populate the modal with serial numbers
      serialNumbers.forEach(serial => {
          const li = document.createElement('li');
          li.textContent = serial;
          serialNumberList.appendChild(li);
      });
      document.getElementById('serialNumberModal').classList.remove('hidden');
  }
  function closeModal() {
    document.getElementById('serialNumberModal').classList.add('hidden');
  }
  
  function openAddSerialModal() {
      document.getElementById('addSerialModal').classList.remove('hidden');
  }
  
  function closeAddSerialModal() {
      document.getElementById('addSerialModal').classList.add('hidden');
  }
  
  function addNewSerial() {
      const newSerial = document.getElementById('newSerialInput').value;
      const productModelName = document.getElementById('productModelName').innerText;
  
      if (newSerial) {
          const li = document.createElement('li');
          li.textContent = newSerial;
          document.getElementById('serialNumberList').appendChild(li);
          closeAddSerialModal(); // Close the add serial modal
      }
  }
  function openAddProductModal() {
      document.getElementById('staticBackdrop').classList.remove('hidden');
  }
  
  function closeAddProductModal() {
      document.getElementById('staticBackdrop').classList.add('hidden');
  }