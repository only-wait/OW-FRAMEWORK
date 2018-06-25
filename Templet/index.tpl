<!DOCTYPE html>
<html>
<head>
	<title>{ow:title}</title>
</head>
<body>
	{ow:content}

	{ow:foreach as ow_key=>ow_value}
		<td>ow_value.id</td>
		<td>ow_value.tag</td>
	{ow:endforeach}

	{ow:for loop=ow_a variable=ow_i}
		<h5>ow_i</h5>
	{ow:endfor}
</body>
</html>