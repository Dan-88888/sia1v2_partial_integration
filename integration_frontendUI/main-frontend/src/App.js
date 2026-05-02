import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";
import Sidebar from "./components/layout/Sidebar";
import Navbar from "./components/layout/Navbar";
import Footer from "./components/layout/Footer";
import Dashboard from "./pages/Dashboard";
import Admission from "./pages/Admission";
import Registration from "./pages/Registration";
import Scheduling from "./pages/Scheduling";
import Map from "./pages/Map";
import Campuses from "./pages/Campuses";
import RoleSelection from "./pages/auth/RoleSelection";
import PortalFrame from "./pages/PortalFrame";
import "./App.css";

const ADMISSION_URL = process.env.REACT_APP_ADMISSION_URL || "http://localhost/admission_system/public";
const REGISTRATION_URL = process.env.REACT_APP_REGISTRATION_URL || "http://localhost/registration_system/public";

function AppShell() {
  return (
    <div className="app-layout">
      <div className="ambient-bg" />
      <Sidebar />
      <div className="app-main">
        <Navbar />
        <main className="app-content relative z-10">
          <Routes>
            <Route path="/dashboard" element={<Dashboard />} />
            <Route path="/admission" element={<Admission />} />
            <Route path="/registration" element={<Registration />} />
            <Route path="/scheduling" element={<Scheduling />} />
            <Route path="/map" element={<Map />} />
            <Route path="/campuses" element={<Campuses />} />
            <Route path="*" element={<Navigate to="/dashboard" replace />} />
          </Routes>
        </main>
        <Footer />
      </div>
    </div>
  );
}

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<RoleSelection />} />
        <Route path="/admin-portal" element={<PortalFrame src={`${ADMISSION_URL}/admin/login`} title="Admin Portal" hideBackButton />} />
        <Route path="/student-portal" element={<PortalFrame src={`${REGISTRATION_URL}/?role=student`} title="Student Portal" />} />
        <Route path="/instructor-portal" element={<PortalFrame src={`${REGISTRATION_URL}/?role=teacher`} title="Instructor Portal" />} />
        <Route path="/admission-form" element={<PortalFrame src={`${ADMISSION_URL}/student/apply`} title="Apply for Admission" hideBackButton />} />
        <Route path="/*" element={<AppShell />} />
      </Routes>
    </Router>
  );
}

export default App;
