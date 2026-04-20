import "./App.css";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import ComingSoon from "./pages/ComingSoon";
import Survey from "./pages/Survey";

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<ComingSoon />} />
          <Route path="/survey" element={<Survey />} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;
