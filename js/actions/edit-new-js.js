window.showEditSettings = showEditSettings;
window.saveEditFormToSession = saveEditFormToSession;
window.setupGetResources = setupGetResources;

function showEditSettings(blockId, languageId) {
  const forms = document.querySelectorAll('.language-form');
  forms.forEach(form => form.style.display = 'none');
  const targetForm = document.getElementById(blockId);

  if (targetForm) {
    targetForm.style.display = 'block';
    sessionStorage.setItem('active-language-block-id', blockId);
    sessionStorage.setItem('active-language-id', languageId);
    const newUrl = '?active=' + encodeURIComponent(languageId) + '&block=' + encodeURIComponent(blockId);
    history.replaceState(null, '', newUrl);
  }
}

document.querySelectorAll(".sidebar-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    window.location.reload();
  });
});

const visibleForm = Array.from(document.querySelectorAll('.language-form')).find(form => {
  return window.getComputedStyle(form).display === 'block';
});

async function saveEditFormToSession() {
  if (!visibleForm) return;

  const formData = new FormData(visibleForm);
  const res = await fetch('../../php-pages/server-side/save_to_session.php', {
    method: 'POST',
    body: formData
  });
  const msg = await res.text();
  console.log(msg);
}

let rowCountRef = { value: 0 };
let rowEditCountRef = { value: 0 };
function setupGetResources(titleName, urlName, storageElementId) {
  const titles = Array.from(document.querySelectorAll(`input[name="${titleName}"]`));
  const urls = Array.from(document.querySelectorAll(`input[name="${urlName}"]`));

  const resources = titles.map((t, i) => ({
    title: t.value.trim(),
    link:   urls[i] ? urls[i].value.trim() : ""
  }));
  document.getElementById(storageElementId).value = JSON.stringify(resources);
}

function createResourceRow(containerId, count, textName, urlName) {
  const container = document.getElementById(containerId);
  const htmlDivElement = document.createElement("div");
  htmlDivElement.className = "resource-div";
  Object.assign(htmlDivElement.style, {
    display: "flex",
    flexDirection: "row",
    gap: "1rem",
    marginBottom: "1rem"
  });

  const createInput = (type, name, id, placeholder, width) => {
    const input = document.createElement("input");
    Object.assign(input, {
      type,
      name,
      id,
      required: true,
      placeholder
    });
    input.className = "language-textarea";
    Object.assign(input.style, {
      width,
      height: "2.2rem",
      padding: "0.5rem"
    });
    return input;
  };

  const textInput = createInput("text", textName, `${textName}-${count}`, "📚 Resource Title", "35%");
  const urlInput = createInput("link", urlName, `${urlName}-${count}`, "🔗 Resource URL", "65%");

  htmlDivElement.appendChild(textInput);
  htmlDivElement.appendChild(urlInput);
  container.appendChild(htmlDivElement);
}

function removeResourceRow(containerId, counterRef) {
  const container = document.getElementById(containerId);
  const rows = container.querySelectorAll(".resource-div");
  if (rows.length > 0) {
    container.removeChild(rows[rows.length - 1]);
    counterRef.value--;
  }
}

document.getElementById("addNewRow").addEventListener("click", function () {
  rowCountRef.value++;
  createResourceRow("resourcesContainer", rowCountRef.value, "resourceText[]", "resourceURL[]");
});

document.getElementById("removeRow").addEventListener("click", function () {
  removeResourceRow("resourcesContainer", rowCountRef);
});

document.getElementById("addNewRow-Edit").addEventListener("click", function () {
  rowEditCountRef.value++;
  createResourceRow("resourcesContainer-Edit", rowEditCountRef.value, "resourceTextEdit[]", "resourceURLEdit[]");
});

document.getElementById("removeRow-Edit").addEventListener("click", function () {
  removeResourceRow("resourcesContainer-Edit", rowEditCountRef);
});
