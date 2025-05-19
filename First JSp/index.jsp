<%@ page import="java.sql.*" %>
<html>
<body>

<p>Hello World !! Welcome to JSP</p>
<h1>Welcome to Sanjivani College of Engineering, IT Department</h1>

<form method="post">
    <h2>Enter Student Details</h2>
    Name: <input type="text" name="name" required><br><br>
    Class: <input type="text" name="class" required><br><br>
    Division: <input type="text" name="division" required><br><br>
    City: <input type="text" name="city" required><br><br>
    <input type="submit" value="Insert Record">
</form>

<hr>

<%

    String name = request.getParameter("name");
    String className = request.getParameter("class"); 
    String division = request.getParameter("division");
    String city = request.getParameter("city");

    try {
        Class.forName("com.mysql.jdbc.Driver");
        Connection con = DriverManager.getConnection("jdbc:mysql://localhost:3306/pragati", "root", "");
        Statement stmt = con.createStatement();

        if (name != null && className != null && division != null && city != null) {
            
            String insertQuery = "INSERT INTO student_info (Name, Class, Division, City) VALUES ('" + name + "', '" + className + "', '" + division + "', '" + city + "')";
            int insertStatus = stmt.executeUpdate(insertQuery);

            if (insertStatus > 0) {
                out.println("<p style='color:green;'>Record inserted successfully!</p>");
            } else {
                out.println("<p style='color:red;'>Record insertion failed.</p>");
            }
        }

       
%>

<table border='5'>
<tr><th>ID</th><th>Name</th><th>Class</th><th>Division</th><th>City</th></tr>

<%
        ResultSet rs = stmt.executeQuery("SELECT * FROM student_info");
        while (rs.next()) {
            out.println("<tr>");
            out.println("<td>" + rs.getInt("id") + "</td>");
            out.println("<td>" + rs.getString("Name") + "</td>");
            out.println("<td>" + rs.getString("Class") + "</td>");
            out.println("<td>" + rs.getString("Division") + "</td>");
            out.println("<td>" + rs.getString("City") + "</td>");
            out.println("</tr>");
        }
        
        rs.close();
        stmt.close();
        con.close();
    } catch (Exception e) { 
        out.print("<p style='color:red;'>Error: " + e.getMessage() + "</p>");
    } 
%>

</table>

</body>
</html>
